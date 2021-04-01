import { http } from './config';

export default {

    index: () => {
        return http.get('cars');
    },

    store: (car) => {
        return http.post('car/store', car);
    },

    update: (car) => {
        return http.put('car/' + car.car.id, car);
    },

    delete: (car) => {
        return http.delete('car/' + car.car.id, car);
    },

    storeReservation: (car) => {
        return http.post('reservation/store/', car);
    },

    deleteReservation: (car) => {
        return http.put('reservation/' + car.reservation.car_id, car);
    }

}