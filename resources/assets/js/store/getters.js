const recent_limit = 5;

export const notificationsArray = (state) => state.notifications;
export const totalNotifications = (state) => state.notifications.length;
export const latestNotifications = (state) => state.notifications.slice(0, recent_limit);

export const messagesArray = (state) => state.messages;
export const totalMessages = (state) => state.messages.length;
