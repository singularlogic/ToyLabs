export const loadNotifications = (state, { list }) => {
    state.notifications.splice(0);
    state.notifications.push(...list);
};

export const clearNotifications = (state) => {
    state.notifications.splice(0);
};

export const addNotification = (state, { notification }) => {
    state.notifications.unshift(notification);
};

export const removeNotification = (state, { notificationId }) => {
    const index = state.notifications.findIndex(n => n.id === notificationId);
    if (index > -1) {
        state.notifications.splice(index, 1);
    }
};

