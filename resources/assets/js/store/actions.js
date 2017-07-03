export const fetchNotifications = ({ commit }) => {
    axios.get('/notifications').then(response => {
        let notifications = [];
        for (let n of response.data.notifications) {
            notifications.push(n.data);
        }
        commit('loadNotifications', { list: notifications });
    });
};

export const getUnreadMessageCount = ({ commit }) => {
    axios.get('/messages/unread').then(response => {
        commit('setUnreadMessages', response.data.counter);
    });
};

export const fetchMessages = ({ commit }) => {
    axios.get('/messages').then(response => {
        commit('loadMessages', { list: response.data });
    });
};

export const markNotificationAsRead = ({ commit }, notification) => {
    axios.patch(`/notifications/${notification.id}/read`);
    commit('removeNotification', { notificationId: notification.id });
};
