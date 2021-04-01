<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::orderBy('created_at', 'DESC')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newUser = new User();
        $err = [];

        //Verifica se existe um cpf
        if (!empty($request->user['cpf'])) {
            $newUser->cpf = $request->user['cpf'];

            //Verifica se o CPF já está registrada
            $cpf = DB::table('users')->where('cpf', $newUser->cpf)->value('cpf');
            if (!empty($cpf)) {
                $err['response'] = 'CPF já está sendo usado!';
                return $err;
            }
        } else
            $err['cpf'] = 'CPF inválida';

        //Verifica se existe um nome
        if (!empty($request->user['name'])) {
            $newUser->name = $request->user['name'];
        } else
            $err['name'] = 'Nome inválido';

        //Caso nenhum erro seja registrado, retorna o objeto criado
        if (empty($err)) {
            $newUser->save();
            return $newUser;
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
        $user = User::find($id);

        if ($user) {
            return $user;
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
        $user = User::find($id);

        if ($user) {
            //Verifica se existe um CPF na request
            if (!empty($request->user['cpf'])) {

                //Verifica se a placa já existe
                $cpf_user_id = DB::table('users')->where('cpf', $request->user['cpf'])->value('id');

                if (!empty($cpf) && ((int) $id) != ((int) $cpf_user_id)) {
                    $err['response'] = 'CPF já está sendo usada!';
                    return $err;
                } else
                    $user->cpf = $request->user['cpf'];
            }

            //Verifica se existe um nome na request
            if (!empty($request->user['name']))
                $user->name = $request->user['name'];

            //Atualiza a data de edição e salva
            //Caso dê erro, o servidor terona uma mensagem
            try {
                $user->updated_at = $request->user['updated_at'] ? Carbon::now() : false;
                $user->save();
                return $user;
            } catch (Exception $e) {
                return "Algo deu errado. Tente novamente!";
                // return $e;
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
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return "Deletado.";
        }

        return "Nenhum item encontrado.";
    }

    //Gera usuários aleatórios
    public function fillUserDatabase()
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->name = "Usuario $i";
            $user->cpf = rand(11111111111, 99999999999);
            $user->save();
        }

        return User::orderBy('created_at', 'DESC')->get();
    }
}
