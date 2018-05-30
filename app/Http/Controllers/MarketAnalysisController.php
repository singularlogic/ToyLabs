<?php

namespace App\Http\Controllers;


use App\MarketAnalysisAnalysis;
use App\MarketAnalysisConcept;
use App\MarketAnalysisProject;
use App\Product;
use App\Providers\Anlzer\AnlzerClient;
use Illuminate\Http\Request;
use Psy\Util\Json;


class MarketAnalysisController extends Controller
{
    protected $anlzerClient;
    public function __construct(AnlzerClient $anlzerClient)
    {
        $this->anlzerClient = $anlzerClient;
    }

    public function index(Request $request, int $product_id)
    {
        $project = new AnlzerProject();
        $analyses = [];

        $model = MarketAnalysisProject::where('product_id', $product_id)->first();
        if ($model !== null) {
            if (is_string($model["anlzer_data"])) {
                $project = $this->parseAnlzerProject($model["anlzer_data"]);
            } else {
                $anlzerProject = $this->anlzerClient->Projects()->getById($model["anlzer_project_id"]);
                $project->id = $anlzerProject->id;
                $project->name = $anlzerProject->name;
                MarketAnalysisProject::where('product_id', $product_id)->update([
                    "name" => $anlzerProject->name,
                    "anlzer_data" => json_encode($anlzerProject)
                ]);
            }

            $analyses = $this->getProjectAnalyses($project->id);
        } else {
            $product = Product::where('id', $product_id)->first();
            $project->name = $product["title"].' market analysis';
        }

        $data = [
            'product_id' => $product_id,
            'project' => $project,
            'analyses' => $analyses
        ];
//        print_r($data);
//        echo json_encode($data['project']);
//        echo $anlzer->Projects()->list();
//        echo $anlzer->Projects()->getById(58);
//        echo $anlzer->Projects()->create();
        return view('product.market', $data);
    }
    private function parseAnlzerProject($anlzer_data)
    {
        if (is_string($anlzer_data)) {
            $anlzer_data = json_decode($anlzer_data);
        }
        $project = new AnlzerProject();
        if (isset($anlzer_data->id))
            $project->id = $anlzer_data->id;
        if (isset($anlzer_data->name))
            $project->name = $anlzer_data->name;
        if (isset($anlzer_data->retriever))
            $project->retriever = $anlzer_data->retriever;
        return $project;
    }
    private function getProjectAnalyses($project_id)
    {
        $analyses = [];
        $models = MarketAnalysisAnalysis::where('anlzer_project_id', $project_id)->get();
        if ($models->count()) {
            foreach ($models as $model){
                $ana = new AnlzerProjectAnalysis();
                $ana->id = $model['anlzer_analysis_id'];
                $ana->name = $model['name'];
                $ana->type = (ANALYSIS_TYPE::SOCIAL === $model['type']) ? ANALYSIS_TYPE::SOCIAL : ANALYSIS_TYPE::TREND;
                array_push($analyses, $ana);
                unset($ana);
            }
        } else {
            $product_id = MarketAnalysisProject::where('anlzer_project_id', $project_id)->first(['product_id'])['product_id'];
            $anlzerProjectAnalyses = $this->anlzerClient->Projects()->listAnalysesById($project_id);
            foreach ($anlzerProjectAnalyses as $anlzerProjectAnalysis){
                $anlzerAnalysis = $this->anlzerClient->Analyses()->getById($anlzerProjectAnalysis->id);
                MarketAnalysisAnalysis::create([
                    "name" => $anlzerAnalysis->name,
                    "type" => ANALYSIS_TYPE::TREND,
                    "anlzer_analysis_id" => $anlzerProjectAnalysis->id,
                    "anlzer_project_id" => $anlzerAnalysis->project,
                    "product_id" => $product_id,
                    "anlzer_data" => json_encode($anlzerAnalysis)
                ]);
                $ana = new AnlzerProjectAnalysis();
                $ana->id = $anlzerProjectAnalysis->id;
                $ana->name = $anlzerProjectAnalysis->name;
                $ana->type = ANALYSIS_TYPE::TREND;
                array_push($analyses, $ana);
                unset($ana);
            }
            $anlzerProjectAnalyses = $this->anlzerClient->Projects()->listFeedbacksById($project_id);
            foreach ($anlzerProjectAnalyses as $anlzerProjectAnalysis){
                $anlzerAnalysis = $this->anlzerClient->Feedbacks()->getById($anlzerProjectAnalysis->id);
                MarketAnalysisAnalysis::create([
                    "name" => $anlzerAnalysis->name,
                    "type" => ANALYSIS_TYPE::SOCIAL,
                    "anlzer_analysis_id" => $anlzerProjectAnalysis->id,
                    "anlzer_project_id" => $anlzerAnalysis->project,
                    "product_id" => $product_id,
                    "anlzer_data" => json_encode($anlzerAnalysis)
                ]);
                $ana = new AnlzerProjectAnalysis();
                $ana->id = $anlzerProjectAnalysis->id;
                $ana->name = $anlzerProjectAnalysis->name;
                $ana->type = ANALYSIS_TYPE::SOCIAL;
                array_push($analyses, $ana);
                unset($ana);
            }
        }

        return $analyses;
    }
    public function create(Request $request, $product_id, AnlzerClient $anlzerClient)
    {
        $input = $request->all();
        $project = new AnlzerProject();
        $analyses = [];
        $model = MarketAnalysisProject::where('product_id', $product_id)->first();
        if ($model !== null) {
            $project = $this->parseAnlzerProject($model["anlzer_data"]);
            $project->id = $model["anlzer_project_id"];
            $project->name = $input["name"];
            $anlzerProject = $anlzerClient->Projects()->update($project->id, $project);
            $project->id = $anlzerProject->id;
            $project->name = $anlzerProject->name;
            MarketAnalysisProject::where('product_id', $product_id)->update([
                "name" => $project->name,
                "anlzer_project_id" => $project->id,
                "anlzer_data" => json_encode($anlzerProject)
            ]);
            $analyses = $this->getProjectAnalyses($project->id);
        } else {
            $project->name = $input["name"];
            $anlzerProject = $anlzerClient->Projects()->create($project);
            $project->id = $anlzerProject->id;
            $project->name = $anlzerProject->name;
            MarketAnalysisProject::create([
                "product_id" => $product_id,
                "name" => $project->name,
                "anlzer_project_id" => $project->id,
                "anlzer_data" => json_encode($anlzerProject)

            ]);
        }


        $data = [
            'product_id' => $product_id,
            'project' => $project,
            'analyses' => $analyses
        ];
        return view('product.market', $data);
    }

    public function trend(Request $request, int $product_id, AnlzerClient $anlzerClient)
    {
//        $project = new AnlzerProject();
//
//        $model = MarketAnalysisProject::where('product_id', $id)->first();
//        if ($model !== null) {
//            $anlzerProject = json_decode($anlzerClient->Projects()->getById($model["anlzer_project_id"]));
//            $project->id = $model["anlzer_project_id"];
//            $project->name = $anlzerProject->name ?: "";
////            $project->keyphrases_list = $anlzerProject->keyphrases_list ?: [];
////            $project->sources = $anlzerProject->sources ?: [];
////            $project->languages = $anlzerProject->languages ?: [];
////            $project->accounts = [];
////            foreach ($anlzerProject->accounts as $account) {
////                $acc = new AnlzerProjectAccount();
////                $acc->name = $account->name;
////                $acc->source = $account->source;
////                $acc->is_influencer = $account->is_influencer;
////                array_push($project->accounts, $acc);
////                unset($acc);
////            }
//            $anlzerProjectAnalyses = json_decode($anlzerClient->Projects()->listAnalysesById($model["anlzer_project_id"]));
//            $project->analyses = [];
//            foreach ($anlzerProjectAnalyses as $anlzerProjectAnalysis){
//                $ana = new AnlzerProjectAnalysis();
//                $ana->id = $anlzerProjectAnalysis->id;
//                $ana->name = $anlzerProjectAnalysis->name;
//                array_push($project->analyses, $ana);
//                unset($ana);
//            }
//        } else {
//            $product = Product::where('id', $id)->first();
//            $project->name = $product["title"].' market analysis';
//        }
//
        $data = [
            'title' => 'Market Trend Analysis',
            'analysis_type' => ANALYSIS_TYPE::TREND,
            'product_id' => $product_id,
            'analysis' => []
        ];
//        print_r($data);
//        echo json_encode($data['project']);
//        echo $anlzer->Projects()->list();
//        echo $anlzer->Projects()->getById(58);
//        echo $anlzer->Projects()->create();
//        echo "'".$id."'";
        return view('product.marketanalysisform', $data);
    }
    public function social(Request $request, int $product_id, AnlzerClient $anlzerClient)
    {
        $data = [
            'title' => 'Social Feedback Analysis',
            'analysis_type' => ANALYSIS_TYPE::SOCIAL,
            'product_id' => $product_id,
            'analysis' => []
        ];
        return view('product.marketanalysisform', $data);
    }

    private function getAnalysisConceptParametersFacets($analysisId)
    {
        $conceptParametersFacets = [];
        $parameters = $this->anlzerClient->Analyses()->getParametersById($analysisId);
        foreach ($parameters as $parameter) {
            $conceptFacets =  $this->anlzerClient->Analyses()->getConceptsParametersById($analysisId, $parameter->id)->concepts_facets;
            $dataTmp = [];
            $labelsTmp = [];
            $index = 0;
            foreach ($conceptFacets as $key => $conceptFacet){
                $labelsTmp[] = [
                    'index' => $index,
                    'label' => $key
                ];
                foreach ($conceptFacet->facets->buckets as $paramName => $bucket){
                    if (!array_key_exists($paramName, $dataTmp)) {
                        $dataTmp[$paramName] = [];
                    }
                    $dataTmp[$paramName]['key']=$paramName;
                    $dataTmp[$paramName]['doc_count_'.$index]=$bucket->doc_count;
                }
                $index++;
            }
//            ksort($dataTmp);
            $conceptParametersFacets[] = [
                "meta" => [
                    "parameter" => [
                        "id" => $parameter->id,
                        "name" => $parameter->name,
                        "values" => $parameter->values,
                        "is_enabled" => $parameter->is_enabled
                    ],
                    "graphs" => $index,
                    "valueFieldPrefix" => 'doc_count_',
                    "labels" => $labelsTmp
                ],
                "data" => array_values($dataTmp)
            ];
        }
        unset($conceptFacets);
        unset($dataTmp);
        unset($labelsTmp);
        unset($index);

        return $conceptParametersFacets;
    }
    private function getAnalysisTimelines($analysisId)
    {
        $timelines =  $this->anlzerClient->Analyses()->getTimelinesById($analysisId)->timelines;
        $dataTmp = [];
        $labelsTmp = [];
        $index = 0;
        foreach ($timelines as $key => $timeline){
            $labelsTmp[] = [
                'index' => $index,
                'label' => $key
            ];
            foreach ($timeline->timeline->buckets as $bucket){
                if (!array_key_exists($bucket->key, $dataTmp)) {
                    $dataTmp[$bucket->key] = [];
                }
                $dataTmp[$bucket->key]['key']=$bucket->key;
                $dataTmp[$bucket->key]['key_as_string']=$bucket->key_as_string;
                $dataTmp[$bucket->key]['doc_count_'.$index]=$bucket->doc_count;
            }

            $index++;
        }
        ksort($dataTmp);
        $timelines = [
            "meta" => [
                "graphs" => $index,
                "valueFieldPrefix" => 'doc_count_',
                "labels" => $labelsTmp
            ],
            "data" => array_values($dataTmp)
        ];
        unset($dataTmp);
        unset($labelsTmp);
        unset($index);

        return $timelines;
    }
    private function getAnalysisTwoWordPhrases($analysisId)
    {
        $twoWordPhrases =  $this->anlzerClient->Analyses()->getTwoWordPhrasesById($analysisId)->{'two-word-phrases'};
        $labelsTmp = [];
        $dataTmp = [];
        $index = 0;
        foreach ($twoWordPhrases as $key => $twoWordPhrase){
            $labelsTmp[] = [
                'index' => $index,
                'label' => $key
            ];
            $dataTmp[] = $twoWordPhrase->{'two-word-phrases'}->buckets;
            $index++;
        }
        ksort($dataTmp);
        $twoWordPhrases = [
            "meta" => [
                "graphs" => $index,
                "labels" => $labelsTmp
            ],
            "data" => $dataTmp
        ];
        unset($dataTmp);
        unset($labelsTmp);
        unset($index);

        return $twoWordPhrases;
    }
    private function getAnalysisConceptTimelines($analysisId)
    {
        $conceptTimelines = $this->anlzerClient->Analyses()->getConseptTimelinesById($analysisId)->concept_timelines;
        $dataTmp = [];
        $labelsTmp = [];
        $index = 0;
        foreach ($conceptTimelines as $key => $conceptTimeline){
            $labelsTmp[] = [
                'index' => $index,
                'label' => $key
            ];
            foreach ($conceptTimeline->timeline->buckets as $bucket){
                if (!array_key_exists($bucket->key, $dataTmp)) {
                    $dataTmp[$bucket->key] = [];
                }
                $dataTmp[$bucket->key]['key']=$bucket->key;
                $dataTmp[$bucket->key]['key_as_string']=$bucket->key_as_string;
                $dataTmp[$bucket->key]['doc_count_'.$index]=$bucket->doc_count;
            }

            $index++;
        }
        ksort($dataTmp);
        $conceptTimelines = [
            "meta" => [
                "graphs" => $index,
                "valueFieldPrefix" => 'doc_count_',
                "labels" => $labelsTmp
            ],
            "data" => array_values($dataTmp)
        ];
        unset($dataTmp);
        unset($labelsTmp);
        unset($index);

        return $conceptTimelines;
    }
    private function getAnalysis($analysis_id, &$model = null)
    {
        if ($model === null) {
            $model = MarketAnalysisAnalysis::where([['anlzer_analysis_id', $analysis_id], ['type', ANALYSIS_TYPE::TREND]])->first();
        }
        if ($model !== null) {
            if ($model['settings'] !== null) {
                $analysis = json_decode($model['settings']);
            } else {
                $anlzerAnalysis = $this->anlzerClient->Analyses()->getById($analysis_id);
                $parameters = [];
                foreach ($this->anlzerClient->Analyses()->getParametersById($analysis_id) as $anlzerParameter) {
                    $parameter = new AnlzerParameter();
                    $parameter->id = $anlzerParameter->id;
                    $parameter->name = $anlzerParameter->name;
                    $parameter->values = $anlzerParameter->values;
                    $parameter->is_enabled = $anlzerParameter->is_enabled;
                    array_push($parameters, $parameter);
                }
                $concepts = [];
                foreach ($anlzerAnalysis->concepts as $conceptId) {
                    $concept = $this->getConceptById($conceptId);
                    array_push($concepts, $concept);
                }
                $analysis = new AnlzerAnalysis();
                $analysis->id = $analysis_id;
                $analysis->name = $anlzerAnalysis->name;
                $analysis->type = ANALYSIS_TYPE::TREND;
                $analysis->project = $anlzerAnalysis->project;
                $analysis->keyphrases_settings = $anlzerAnalysis->keyphrases_settings;
                $analysis->sources = $anlzerAnalysis->sources;
                $analysis->languages = $anlzerAnalysis->languages;
                $analysis->influencers_mode = $anlzerAnalysis->influencers_mode;
                $analysis->start_date = $anlzerAnalysis->start_date;
                $analysis->end_date = $anlzerAnalysis->end_date;
                $analysis->concepts = $concepts;
                $analysis->parameters = $parameters;

                $model->update([
                    "name" => $analysis->name,
                    "type" => $analysis->type,
                    "anlzer_project_id" => $analysis->project,
                    "anlzer_data" => json_encode($anlzerAnalysis),
                    "settings" => json_encode($analysis)
                ]);
            }
            return $analysis;
        } else {
            abort(404);
        }
    }
    private function getConceptById(int $concept_id)
    {
        $concept = new AnlzerConcept();
        $model = MarketAnalysisConcept::where('anlzer_concept_id', $concept_id)->first();
        if ($model !== null) {
            if ($model['anlzer_data'] !== null) {
                $anlzer_data = json_decode($model['anlzer_data']);
            } else {
                $anlzer_data = $this->anlzerClient->Concepts()->getById($concept_id);
                $model->update([
                    "name" => $anlzer_data->name,
                    "anlzer_concept_id" => $anlzer_data->id,
                    "anlzer_project_id" => $anlzer_data->project,
                    "anlzer_data" => json_encode($anlzer_data)
                ]);
            }
            $concept->id = $anlzer_data->id;
            $concept->name = $anlzer_data->name;
            $concept->values = $anlzer_data->values;
        } else {
            $anlzer_data = $this->anlzerClient->Concepts()->getById($concept_id);
            $concept->id = $anlzer_data->id;
            $concept->name = $anlzer_data->name;
            $concept->values = $anlzer_data->values;

            MarketAnalysisConcept::create([
                "name" => $anlzer_data->name,
                "anlzer_concept_id" => $anlzer_data->id,
                "anlzer_project_id" => $anlzer_data->project,
                "anlzer_data" => json_encode($anlzer_data)
            ]);
        }
        return $concept;
    }
    private function getAnalysisChartData($analysis_id, &$model = null)
    {
        if ($model === null) {
            $model = MarketAnalysisAnalysis::where([['anlzer_analysis_id', $analysis_id], ['type', ANALYSIS_TYPE::TREND]])->first();
        }
        if ($model !== null) {
            if ($model['data'] !== null) {
                $chart_data = json_decode($model['data']);
            } else {
                $chart_data = [
                    'top_hashtags' => $this->anlzerClient->Analyses()->getTopHashtagsById($analysis_id),
                    'top_accounts' => $this->anlzerClient->Analyses()->getTopAccountsById($analysis_id),
                    'overall_timeline' => $this->anlzerClient->Analyses()->getOverallTimelinesById($analysis_id),
                    'timelines' => $this->getAnalysisTimelines($analysis_id),
                    'two_word_phrases' => $this->getAnalysisTwoWordPhrases($analysis_id),
                    'concept_timelines' => $this->getAnalysisConceptTimelines($analysis_id),
                    'conceptParametersFacets' => $this->getAnalysisConceptParametersFacets($analysis_id)
                ];

                $model->update([
                    "data" => json_encode($chart_data)
                ]);
            }
            return $chart_data;
        } else {
            abort(404);
        }
    }
    public function showAnalysis($analysis_id)
    {
        $model = MarketAnalysisAnalysis::where([['anlzer_analysis_id', $analysis_id], ['type', ANALYSIS_TYPE::TREND]])->first();
        if ($model !== null) {
            $analysis = $this->getAnalysis($analysis_id, $model);
            $chart_data = $this->getAnalysisChartData($analysis_id, $model);

            $data = [
                'title'  => $analysis->name,
                'analysis_type' => ANALYSIS_TYPE::TREND,
                'analysis_id' => $analysis->id,
                'product_id' => $model['product_id'],
                'analysis' => $analysis,
                'chart_data' => $chart_data,
                'meta' => [
                    'likeCount' => 0,
                    'liked' => false,
                    'comments' => []
                ],
                'model'  => [
                    'type' => 'analysis',
                    'id'   => $analysis_id,
                ],
            ];

            return view('product.analysis', $data);
        } else {
            abort(404);
        }
    }
    public function editAnalysis($analysis_id)
    {
        $model = MarketAnalysisAnalysis::where([['anlzer_analysis_id', $analysis_id], ['type', ANALYSIS_TYPE::TREND]])->first();
        if ($model !== null) {
            $analysis = $this->getAnalysis($analysis_id, $model);

            $data = [
                'title'  => $analysis->name,
                'analysis_id' => $analysis->id,
                'analysis_type' => $analysis->type,
                'product_id' => $model['product_id'],
                'analysis' => $analysis
            ];

            return view('product.marketanalysisform', $data);

        } else {
            abort(404);
        }
    }
    public function saveAnalysis(Request $request, int $analysis_id)
    {

        $input = $request->all();

        $product_id = $input['product_id'];
        $project_id =  MarketAnalysisProject::where('product_id', $product_id)->first(['anlzer_project_id'])['anlzer_project_id'];

        $concepts = json_decode($input['concepts']);
        $conceptIds = [];
        foreach ($concepts as $concept){
            $anlzerconcept = new AnlzerConcept();
            $anlzerconcept->project = $project_id;
            $anlzerconcept->name = $concept->name;
            $anlzerconcept->values = $concept->values;
            if ($concept->id === 0) {
                $anlzer_data = $this->anlzerClient->Concepts()->create($anlzerconcept);
                array_push($conceptIds, $anlzer_data->id);
            } else {
                $anlzerconcept->id = $concept->id;
                array_push($conceptIds, $concept->id);
            }
        }



        $keyphrases_settings = [];
        foreach (json_decode($input['keyphrases_settings']) as $keyphrase){
            array_push($keyphrases_settings, [
                "type" => $keyphrase->type,
                "value" => $keyphrase->value
            ]);
        }

        $anlzerAnalysis = new AnlzerAnalysis();
        $anlzerAnalysis->name = $input['name'];
        $anlzerAnalysis->project = $project_id;
        $anlzerAnalysis->keyphrases_settings = $keyphrases_settings;
        $anlzerAnalysis->start_date = $input['start_date'];
        $anlzerAnalysis->end_date = $input['end_date'];
        $anlzerAnalysis->concepts = $conceptIds;
        $anlzerAnalysis->influencers_mode = ($input['influencers_mode']) ? $input['influencers_mode'] : false;
        $anlzerAnalysis->sources = json_decode($input['sources']);
        $anlzerAnalysis->languages = ($input['languages']) ? json_decode($input['languages']) : ["en"];

        switch($input['action']) {
            case 'create':
            case 'copy':
                $anlzer_data = $this->anlzerClient->Analyses()->create($anlzerAnalysis);
                $analysis_id = $anlzer_data->id;
                MarketAnalysisAnalysis::create([
                    "name" => $anlzer_data->name,
                    "type" => ANALYSIS_TYPE::TREND,
                    "anlzer_analysis_id" => $anlzer_data->id,
                    "anlzer_project_id" => $anlzer_data->project,
                    "product_id" => $product_id,
                    "anlzer_data" => json_encode($anlzer_data)
                ]);
                break;
            case 'save':
                $anlzerAnalysis->id = $analysis_id;
                $anlzer_data = $this->anlzerClient->Analyses()->update($analysis_id, $anlzerAnalysis);
                $analysis_id = $anlzer_data->id;
                MarketAnalysisAnalysis::where([['anlzer_analysis_id', $analysis_id], ['type', ANALYSIS_TYPE::TREND]])->update([
                    "name" => $anlzer_data->name,
                    "type" => ANALYSIS_TYPE::TREND,
                    "anlzer_analysis_id" => $anlzer_data->id,
                    "anlzer_project_id" => $anlzer_data->project,
                    "product_id" => $product_id,
                    "anlzer_data" => json_encode($anlzer_data),
                    "data" => null,
                    "settings" => null
                ]);
                break;
        }
        $parameters = json_decode($input['parameters']);
        $parameterIds = [];
        foreach ($parameters as $parameter) {
            if ($input['action'] !== "save") {
                $parameter->id = 0;
            }
            if ($parameter->id === 0) {
                $anlzerparameter = new AnlzerParameter();
                $anlzerparameter->analysis = $analysis_id;
                $anlzerparameter->name = $parameter->name;
                $anlzerparameter->values = $parameter->values;
                $anlzerparameter->is_enabled = $parameter->is_enabled;
                $anlzer_data = $this->anlzerClient->Parameters()->create($anlzerparameter);
                array_push($parameterIds, $anlzer_data->id);
            } else {
                array_push($parameterIds, $parameter->id);
            }
        }
        // Cleanup deleted parameters
        $all_parameters = $this->anlzerClient->Analyses()->getParametersById($analysis_id);
        foreach ($all_parameters as $param) {
            if (!in_array($param->id, $parameterIds)) {
                $this->anlzerClient->Parameters()->delete($param->id);
            }
        }

        return redirect()->route('product.analysis', [$product_id]);
    }

    private function getFeedbackTimelines($analysisId)
    {
        $timelines =  $this->anlzerClient->Feedbacks()->getTimelinesById($analysisId)->timelines;
        $dataTmp = [];
        $labelsTmp = [];
        $index = 0;
        foreach ($timelines as $key => $timeline){
            $labelsTmp[] = [
                'index' => $index,
                'label' => $key
            ];
            foreach ($timeline->timeline->buckets as $bucket){
                if (!array_key_exists($bucket->key, $dataTmp)) {
                    $dataTmp[$bucket->key] = [];
                }
                $dataTmp[$bucket->key]['key']=$bucket->key;
                $dataTmp[$bucket->key]['key_as_string']=$bucket->key_as_string;
                $dataTmp[$bucket->key]['doc_count_'.$index]=$bucket->doc_count;
            }

            $index++;
        }
        ksort($dataTmp);
        $timelines = [
            "meta" => [
                "graphs" => $index,
                "valueFieldPrefix" => 'doc_count_',
                "labels" => $labelsTmp
            ],
            "data" => array_values($dataTmp)
        ];
        unset($dataTmp);
        unset($labelsTmp);
        unset($index);

        return $timelines;
    }
    private function getFeedbackBrandTimelines($analysisId)
    {
        $timelines =  $this->anlzerClient->Feedbacks()->getBrandTimelinesById($analysisId)->timelines;
        $dataTmp = [];
        $labelsTmp = [];
        $index = 0;
        foreach ($timelines as $key => $timeline){
            $labelsTmp[] = [
                'index' => $index,
                'label' => $key
            ];
            foreach ($timeline->timeline->buckets as $bucket){
                if (!array_key_exists($bucket->key, $dataTmp)) {
                    $dataTmp[$bucket->key] = [];
                }
                $dataTmp[$bucket->key]['key']=$bucket->key;
                $dataTmp[$bucket->key]['key_as_string']=$bucket->key_as_string;
                $dataTmp[$bucket->key]['doc_count_'.$index]=$bucket->doc_count;
            }

            $index++;
        }
        ksort($dataTmp);
        $timelines = [
            "meta" => [
                "graphs" => $index,
                "valueFieldPrefix" => 'doc_count_',
                "labels" => $labelsTmp
            ],
            "data" => array_values($dataTmp)
        ];
        unset($dataTmp);
        unset($labelsTmp);
        unset($index);

        return $timelines;
    }
    private function getFeedbackProductTimelines($analysisId)
    {
        $timelines =  $this->anlzerClient->Feedbacks()->getProductTimelinesById($analysisId)->timelines;
        $dataTmp = [];
        $labelsTmp = [];
        $index = 0;
        foreach ($timelines as $key => $timeline){
            $labelsTmp[] = [
                'index' => $index,
                'label' => $key
            ];
            foreach ($timeline->timeline->buckets as $bucket){
                if (!array_key_exists($bucket->key, $dataTmp)) {
                    $dataTmp[$bucket->key] = [];
                }
                $dataTmp[$bucket->key]['key']=$bucket->key;
                $dataTmp[$bucket->key]['key_as_string']=$bucket->key_as_string;
                $dataTmp[$bucket->key]['doc_count_'.$index]=$bucket->doc_count;
            }

            $index++;
        }
        ksort($dataTmp);
        $timelines = [
            "meta" => [
                "graphs" => $index,
                "valueFieldPrefix" => 'doc_count_',
                "labels" => $labelsTmp
            ],
            "data" => array_values($dataTmp)
        ];
        unset($dataTmp);
        unset($labelsTmp);
        unset($index);

        return $timelines;
    }

    private function getFeedbackWordPhrases($analysisId, $words=WORD_PHRASE_WORDS::THREE, $type=WORD_PHRASE_TYPE::BRAND)
    {
        switch ($words){
            case WORD_PHRASE_WORDS::ONE:
                $_words = WORD_PHRASE_WORDS::ONE;
                break;
            case WORD_PHRASE_WORDS::TWO:
                $_words = WORD_PHRASE_WORDS::TWO;
                break;
            case WORD_PHRASE_WORDS::THREE:
            default:
                $_words = WORD_PHRASE_WORDS::THREE;
                break;
        }
        switch ($type){
            case WORD_PHRASE_TYPE::PRODUCT:
                $_type = WORD_PHRASE_TYPE::PRODUCT;
                break;
            case WORD_PHRASE_TYPE::BRAND:
            default:
            $_type = WORD_PHRASE_TYPE::BRAND;
                break;
        }
        $_api_method = "get{$_words}WordPhrases{$_type}ById";
        $_api_return = strtolower("{$_words}-word-phrases");

        $wordPhrases =  $this->anlzerClient->Feedbacks()->$_api_method($analysisId)->$_api_return;
        $labelsTmp = [];
        $dataTmp = [];
        $index = 0;
        foreach ($wordPhrases as $key => $wordPhrase){
            $labelsTmp[] = [
                'index' => $index,
                'label' => $key,
                'doc_count' => $wordPhrase->doc_count
            ];
            $dataTmp[] = $wordPhrase->$_api_return->buckets;
            $index++;
        }
        ksort($dataTmp);
        $wordPhrases = [
            "meta" => [
                "graphs" => $index,
                "labels" => $labelsTmp
            ],
            "data" => $dataTmp
        ];
        unset($dataTmp);
        unset($labelsTmp);
        unset($index);

        return $wordPhrases;
    }
    private function getFeedbackChartData($analysis_id, &$model = null)
    {
        if ($model === null) {
            $model = MarketAnalysisAnalysis::where([['anlzer_analysis_id', $analysis_id], ['type', ANALYSIS_TYPE::SOCIAL]])->first();
        }
        if ($model !== null) {
            if ($model['data'] !== null) {
                $chart_data = json_decode($model['data']);
            } else {
                $chart_data = [
                    'top_hashtags' => $this->anlzerClient->Feedbacks()->getTopHashtagsById($analysis_id),
                    'top_accounts' => $this->anlzerClient->Feedbacks()->getTopAccountsById($analysis_id),
                    'overall_timeline' => $this->anlzerClient->Feedbacks()->getOverallTimelinesById($analysis_id),
                    'timelines' => $this->getFeedbackTimelines($analysis_id),
                    'brand_timelines' => $this->getFeedbackBrandTimelines($analysis_id),
                    'product_timelines' => $this->getFeedbackProductTimelines($analysis_id),
                    'sentiments' => $this->anlzerClient->Feedbacks()->getSentimentsById($analysis_id),
                    'one_word_phrases_brands' => $this->getFeedbackWordPhrases($analysis_id,WORD_PHRASE_WORDS::ONE, WORD_PHRASE_TYPE::BRAND),
                    'one_word_phrases_products' => $this->getFeedbackWordPhrases($analysis_id,WORD_PHRASE_WORDS::ONE, WORD_PHRASE_TYPE::PRODUCT),
                    'two_word_phrases_brands' => $this->getFeedbackWordPhrases($analysis_id,WORD_PHRASE_WORDS::TWO, WORD_PHRASE_TYPE::BRAND),
                    'two_word_phrases_products' => $this->getFeedbackWordPhrases($analysis_id,WORD_PHRASE_WORDS::TWO, WORD_PHRASE_TYPE::PRODUCT),
                    'three_word_phrases_brands' => $this->getFeedbackWordPhrases($analysis_id,WORD_PHRASE_WORDS::THREE, WORD_PHRASE_TYPE::BRAND),
                    'three_word_phrases_products' => $this->getFeedbackWordPhrases($analysis_id,WORD_PHRASE_WORDS::THREE, WORD_PHRASE_TYPE::PRODUCT),
                ];

                $model->update([
                    "data" => json_encode($chart_data)
                ]);
            }
            return $chart_data;
        } else {
            abort(404);
        }
    }
    private function getFeedback($anlzer_analysis_id, &$model = null)
    {
        if ($model === null) {
            $model = MarketAnalysisAnalysis::where([['anlzer_analysis_id', $anlzer_analysis_id], ['type', ANALYSIS_TYPE::SOCIAL]])->first();
        }
        if ($model !== null) {
            if ($model['settings'] !== null) {
                $analysis = json_decode($model['settings']);
            } else {
                $anlzerAnalysis = $this->anlzerClient->Feedbacks()->getById($anlzer_analysis_id);
                $analysis = new AnlzerFeedback();
                $analysis->id = $anlzer_analysis_id;
                $analysis->name = $anlzerAnalysis->name;
                $analysis->type = ANALYSIS_TYPE::SOCIAL;
                $analysis->project = $anlzerAnalysis->project;
                $analysis->keyphrases_settings = $anlzerAnalysis->keyphrases_settings;
                $analysis->sources = $anlzerAnalysis->sources;
                $analysis->languages = $anlzerAnalysis->languages;
                $analysis->start_date = $anlzerAnalysis->start_date;
                $analysis->end_date = $anlzerAnalysis->end_date;

                $model->update([
                    "name" => $analysis->name,
                    "type" => $analysis->type,
                    "anlzer_project_id" => $analysis->project,
                    "anlzer_data" => json_encode($anlzerAnalysis),
                    "settings" => json_encode($analysis)
                ]);
            }
            return $analysis;
        } else {
            abort(404);
        }
    }
    public function showFeedback($analysis_id)
    {
        $model = MarketAnalysisAnalysis::where([['anlzer_analysis_id', $analysis_id], ['type', ANALYSIS_TYPE::SOCIAL]])->first();
        if ($model !== null) {
            $analysis = $this->getFeedback($analysis_id, $model);
            $chart_data = $this->getFeedbackChartData($analysis_id, $model);

            $data = [
                'title'  => $analysis->name,
                'analysis_type' => ANALYSIS_TYPE::SOCIAL,
                'analysis_id' => $analysis->id,
                'product_id' => $model['product_id'],
                'analysis' => $analysis,
                'chart_data' => $chart_data,
                'meta' => [
                    'comments' => []
                ],
                'model'  => [
                    'type' => 'analysis',
                    'id'   => $analysis_id,
                ],
            ];

            return view('product.analysis', $data);
        } else {
            abort(404);
        }
    }
    public function editFeedback($analysis_id)
    {
        $model = MarketAnalysisAnalysis::where([['anlzer_analysis_id', $analysis_id], ['type', ANALYSIS_TYPE::SOCIAL]])->first();
        if ($model !== null) {
            $analysis = $this->getFeedback($analysis_id, $model);

            $data = [
                'title'  => $analysis->name,
                'analysis_id' => $analysis->id,
                'analysis_type' => $analysis->type,
                'product_id' => $model['product_id'],
                'analysis' => $analysis
            ];

            return view('product.marketanalysisform', $data);

        } else {
            abort(404);
        }
    }
    public function saveFeedback(Request $request, int $anlzer_analysis_id)
    {

        $input = $request->all();

        $product_id = $input['product_id'];
        $anlzer_project_id =  MarketAnalysisProject::where('product_id', $product_id)->first(['anlzer_project_id'])['anlzer_project_id'];

        $keyphrases_settings = [];
        foreach (json_decode($input['keyphrases_settings']) as $keyphrase){
            array_push($keyphrases_settings, [
                "type" => $keyphrase->type,
                "value" => $keyphrase->value
            ]);
        }

        $anlzerAnalysis = new AnlzerFeedback();
        $anlzerAnalysis->name = $input['name'];
        $anlzerAnalysis->project = $anlzer_project_id;
        $anlzerAnalysis->keyphrases_settings = $keyphrases_settings;
        $anlzerAnalysis->start_date = $input['start_date'];
        $anlzerAnalysis->end_date = $input['end_date'];
        $anlzerAnalysis->sources = json_decode($input['sources']);
        $anlzerAnalysis->languages = ($input['languages']) ? json_decode($input['languages']) : ["en"];

        switch($input['action']) {
            case 'create':
            case 'copy':
                $anlzer_data = $this->anlzerClient->Feedbacks()->create($anlzerAnalysis);
                $anlzer_analysis_id = $anlzer_data->id;
                MarketAnalysisAnalysis::create([
                    "name" => $anlzer_data->name,
                    "type" => ANALYSIS_TYPE::SOCIAL,
                    "anlzer_analysis_id" => $anlzer_data->id,
                    "anlzer_project_id" => $anlzer_data->project,
                    "product_id" => $product_id,
                    "anlzer_data" => json_encode($anlzer_data)
                ]);
                break;
            case 'save':
                $anlzerAnalysis->id = $anlzer_analysis_id;
                $anlzer_data = $this->anlzerClient->Feedbacks()->update($anlzer_analysis_id, $anlzerAnalysis);
                $anlzer_analysis_id = $anlzer_data->id;
                MarketAnalysisAnalysis::where([['anlzer_analysis_id', $anlzer_analysis_id], ['type', ANALYSIS_TYPE::SOCIAL]])->update([
                    "name" => $anlzer_data->name,
                    "type" => ANALYSIS_TYPE::SOCIAL,
                    "anlzer_analysis_id" => $anlzer_data->id,
                    "anlzer_project_id" => $anlzer_data->project,
                    "product_id" => $product_id,
                    "anlzer_data" => json_encode($anlzer_data),
                    "data" => null,
                    "settings" => null
                ]);
                break;
        }

        return redirect()->route('product.analysis', [$product_id]);
    }
}

class AnlzerProject {
    public $id = 0;
    public $name = "";
    public $retriever = null;
}


class AnlzerProjectAccount {
    public $name = "";
    public $source = "";
    public $is_influencer = false;
}

class AnlzerProjectAnalysis {
    public $id = 0;
    public $name = "";
    public $type = ANALYSIS_TYPE::TREND;
}

abstract class ANALYSIS_TYPE {
    const TREND = 'trend';
    const SOCIAL = 'social';
}

abstract class WORD_PHRASE_WORDS {
    const ONE = 'One';
    const TWO = 'Two';
    const THREE = 'Three';
}
abstract class WORD_PHRASE_TYPE {
    const BRAND = 'br';
    const PRODUCT = 'pr';
}

class AnlzerAnalysis {
    public $id = 0;
    public $name = "";
    public $type = ANALYSIS_TYPE::TREND;
    public $project = 0;
    public $keyphrases_settings = [];
    public $start_date = 0;
    public $end_date = 0;
    public $concepts = [];
    public $parameters = [];
    public $influencers_mode = false;
    public $sources = [];
    public $languages = ["en"];
}

class AnlzerParameter {
    public $id = 0;
    public $name = "";
    public $values = "";
    public $analysis = 0;
    public $is_enabled = false;
}

class AnlzerConcept {
    public $id = 0;
    public $name = "";
    public $project = 0;
    public $values = [];
}

class AnlzerRetriever {
    public $id = 0;
    public $name = "";
    public $keyphrases_list = [];
    public $languages = ["en"];
    public $accounts = [];
}

class AnlzerFeedback {
    public $id = 0;
    public $name = "";
    public $type = ANALYSIS_TYPE::SOCIAL;
    public $project = 0;
    public $keyphrases_settings = [];
    public $start_date = 0;
    public $end_date = 0;
    public $sources = [];
    public $languages = ["en"];
}