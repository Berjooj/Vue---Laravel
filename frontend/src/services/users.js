import { http } from './config';

export default {

    index: () => {
        return http.get('users');
    },

    store: (user) => {
        return http.post('user/store', user);
    },

    update: (user) => {
        return http.put('user/' + user.user.id, user);
    },

    delete: (user) => {
        return http.delete('user/' + user.user.id, user);
    }

}