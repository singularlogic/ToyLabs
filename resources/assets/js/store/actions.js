export const fetchNotifications = ({ commit }) => {
    axios.get('/notifications').then(response => {
        let notifications = [];
        for (let n of response.data.notifications) {
            notifications.push(n.data);
        }
        commit('loadNotifications', { list: notifications });
    });
};

export const markNotificationAsRead = ({ commit }, notification) => {
    axios.patch(`/notifications/${notification.id}/read`);
    commit('removeNotification', { notificationId: notification.id });
};
