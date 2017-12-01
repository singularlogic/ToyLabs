const recent_limit = 5;

export const userID = (state) => state.user ? state.user.id : -1;
export const isLogged = (state) => state.user !== null;
export const isGuest = (state) => state.user === null;

export const notificationsArray = (state) => state.notifications;
export const totalNotifications = (state) => state.notifications.length;
export const latestNotifications = (state) => state.notifications.slice(0, recent_limit);

export const messagesArray = (state) => state.messages;
export const unreadMessages = (state) => state.unreadMessagesCounter;
export const totalMessages = (state) => state.messages.length;

export const activeThread = (state) => state.activeThread;
export const activePartner = (state) => state.activePartner;