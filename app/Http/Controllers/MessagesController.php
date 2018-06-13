<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Message;
use App\Notifications\NewMessageNotification;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class MessagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show all threads that user is participating in
     *
     * @return mixed
     */
    public function index()
    {
        $threads = Thread::forUser(Auth::id())->latest('updated_at')->get();

        $result = [];
        foreach ($threads as $thread) {
            $tmp            = $thread;
            $tmp['creator'] = $thread->creator();
            $tmp['replies'] = count($thread->messages);
            $result[]       = $tmp;
        }

        return $result;
    }

    /**
     * Show all threads that user is participating in, with new messages
     *
     * @return mixed
     */
    public function indexNew()
    {
        $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();

        $result = [];
        foreach ($threads as $thread) {
            $tmp            = $thread;
            $tmp['creator'] = $thread->creator();
            $result[]       = $tmp;
        }

        return $result;
    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $thread = Thread::with('target')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $error = 'Thread not found';
            return compact('error');
        }

        if (\Gate::denies('view.thread', $thread)) {
            abort(401, 'Unauthorized access');
        }

        $userId   = Auth::user()->id;
        $users    = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();
        $messages = Message::where('thread_id', $id)->with('user')->get();
        $thread->markAsRead($userId);

        return compact('thread', 'messages', 'users');
    }

    /**
     * Gets all the users (except self)
     *
     * @return mixed
     */
    public function users()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return compact('users');
    }

    /**
     * Gets number of unread messages
     *
     * @return mixed
     */
    public function unread()
    {
        return Auth::user()->newThreadsCount();
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store()
    {
        $input  = Input::all();
        $thread = Thread::create([
            'subject' => $input['subject'],
        ]);

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id'   => Auth::user()->id,
            'body'      => $input['body'],
        ]);
        $files = isset($input['files']) ? $input['files'] : [];
        foreach ($files as $file) {
            $path = storage_path('/app/' . $file['path']);
            $message->addMedia($path)->usingName($file['name'])->toMediaCollection('files');
        }

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id'   => Auth::user()->id,
            'last_read' => new Carbon,
        ]);

        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipant($input['recipients']);
        }

        // Notify Participants
        foreach ($input['recipients'] as $recipient) {
            // Don't notify myself!
            if ($recipient === Auth::user()->id) {
                continue;
            }

            event(new NewMessage($recipient, $thread->id));
            $recipient->notify(new NewMessageNotification($recipient, $thread));
        }

        return compact('message');
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error', 'The thread was not found.');
            return redirect('messages');
        }
        $thread->activateAllParticipants();

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id'   => Auth::id(),
            'body'      => Input::get('message'),
        ]);
        $files = Input::get('files') ?: [];
        foreach ($files as $file) {
            $path = storage_path('/app/' . $file['path']);
            $message->addMedia($path)->usingName($file['name'])->toMediaCollection('files');
        }

        // Add replier as a participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id'   => Auth::user()->id,
        ]);
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipant(Input::get('recipients'));
        }

        foreach ($thread->participants as $participant) {
            $user = $participant->user;
            if ($user !== null && $user !== Auth::user()) {
                event(new NewMessage($user->id, $thread->id));
                $user->notify(new NewMessageNotification($user, $thread));
            }
        }

        $message['user'] = Auth::user();

        return compact('message');
    }
}
