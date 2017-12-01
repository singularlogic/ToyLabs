export const loadNotifications = (state, { list }) => {
    state.notifications.splice(0);
    state.notifications.push(...list);
};

export const loadMessages = (state, { list }) => {
    state.messages.splice(0);
    state.messages.push(...list);
};

export const setUnreadMessages = (state, count) => {
    state.unreadMessagesCounter = count;
};

export const messageArrived = (state) => {
    state.unreadMessagesCounter++;
}

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

export const setActiveThread = (state, { thread }) => {
    state.activeThread = thread;
};

export const setOrganization = (state, { organization }) => {
    state.activePartner = organization;
};