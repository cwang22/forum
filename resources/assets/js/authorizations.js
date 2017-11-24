const user = window.App.user;

module.exports = {
    owns(model, props = 'user_id') {
        return model[props] === user.id
    },
    isAdmin () {
        return ['i@seewang.me'].includes(user.email);
    }
};