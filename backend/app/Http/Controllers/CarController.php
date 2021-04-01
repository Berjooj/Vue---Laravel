<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Car::orderBy('created_at', 'DESC')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newCar = new Car();
        $err = [];

        //Verifica se existe uma placa
        if (!empty($request->car['plate'])) {
            $newCar->plate = $request->car['plate'];

            //Verifica se a placa já está registrada
            $plate = DB::table('cars')->where('plate', $newCar->plate)->value('plate');
            if (!empty($plate)) {
                $err['response'] = 'Placa já está sendo usada!';
                return $err;
            }
        } else
            $err['plate'] = 'Placa inválida';

        //Verifica se existe uma marca
        if (!empty($request->car['brand'])) {
            $newCar->brand = $request->car['brand'];
        } else
            $err['brand'] = 'Marca inválida';

        //Verifica se existe um ano
        if (!empty($request->car['year'])) {
            $newCar->year = $request->car['year'];
        } else
            $err['year'] = 'Ano inválido';

        //Caso nenhum erro seja registrado, retorna o objeto criado
        if (empty($err)) {
            $newCar->save();
            return $newCar;
        }


        //Caso algum erro seja registrado, retorna o array de erros com os campos incorretos
        $err['response'] = 'Alguns elementos estão errados!';
        return $err;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $car = Car::find($id);

        if ($car) {
            return $car;
        }
        return "Nenhum item encontrado.";
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $car = Car::find($id);

        if ($car) {
            //Verifica se existe uma placa na request
            if (!empty($request->car['plate'])) {

                //Verifica se a placa já existe
                $plate_user_id = DB::table('cars')->where('id', $id)->value('plate');

                if (!empty($plate) && ((int) $id) != ((int) $plate_user_id)) {
                    $err['response'] = 'Placa já está sendo usada!';
                    return $err;
                } else
                    $car->plate = $request->car['plate'];
            }

            //Verifica se existe uma marca na request
            if (!empty($request->car['brand']))
                $car->brand = $request->car['brand'];

            //Verifica se existe um ano na request
            if (!empty($request->car['year']))
                $car->year = $request->car['year'];

            //Atualiza a data de edição e salva
            //Caso dê erro, o servidor terona uma mensagem
            try {
                $car->updated_at = $request->car['updated_at'] ? Carbon::now() : false;
                $car->save();

                return $car;
            } catch (Exception $e) {
                return "Algo deu errado. Tente novamente!";
            }
        }

        return "Nenhum item encontrado.";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = Car::find($id);

        if ($car) {
            $car->delete();
            return "Deletado.";
        }

        return "Nenhum item encontrado.";
    }


    //Mostra todas as reservas
    public function indexReservation()
    {
        return Car::orderBy('created_at', 'DESC')->WhereNotNull('user_id')->get();
    }

    //Mostra reservas por ID de usuário
    public function showReservation($id)
    {
        return Car::orderBy('created_at', 'DESC')->Where('user_id', $id)->get();
    }

    public function storeReservation(Request $request)
    {
        $user_id = $request->reservation['user_id'];
        $car_id = $request->reservation['car_id'];
        //Busca um carro por id para validá-lo
        $car = Car::find($car_id);

        //Busca um usuário por id para validá-lo
        $user = User::find($user_id);

        try {
            //Verifica se o carro e o usuário existem
            if ($car != null && $user != null) {
                //Verifica se o carro está alugado
                if ($car->user_id == null) {
                    $car->user_id = $user_id;

                    //Registra o aluguel
                    $car->save();

                    //Gera o log
                    Log::channel('reservation')->info('Um carro acabou de ser reservado', [
                        'car_id' => $car_id,
                        'user_id' => $user_id
                    ]);

                    return $car;
                } else
                    return "O carro já está sendo alugado!";
            } else {
                return "O carro ou usuário não existem!";
            }
        } catch (Exception $e) {
            return "Opa, algo deu errado!";
        }
    }

    public function destroyReservation(Request $request, $car_id)
    {
        //Busca um carro por id para validá-lo
        $car = Car::find($car_id);

        try {
            //Verifica se o carro e o usuário existem
            $user_id = $request->reservation['user_id'];

            if ($car != null) {
                //Verifica se o carro está alugado
                if ($car->user_id != null && $car->user_id == $user_id) {
                    $car->user_id = null;

                    //Registra o aluguel
                    $car->save();

                    //Gera o log
                    Log::channel('reservation')->info('Um carro acabou de ser devolvido', [
                        'car_id' => $car_id,
                        'user_id' => $user_id
                    ]);

                    return $car;
                } else
                    return "O carro não está alugado por você!";
            } else {
                return "O carro não existe!";
            }
        } catch (Exception $e) {
            return "Opa, algo deu errado!";
        }
    }

    //Gera carros aleatórios
    public function fillCarDatabase()
    {
        for ($i = 0; $i < 10; $i++) {
            $car = new Car();
            $car->brand = "Marca $i";
            $car->year = rand(1999, 2021);
            $car->plate = rand(1000000, 90000000);
            $car->save();
        }

        return Car::orderBy('created_at', 'DESC')->get();
    }
}
