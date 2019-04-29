<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(sys_estado_br::class);
        $this->call(sys_cidade_br::class);
        $this->call(endereco_seed::class);
        User::create([
            'nome'=> 'Augusto Furlan',
            'email'=> 'gulyfurlan@gmail.com',
            'password'=>bcrypt('123123'), 
            'role'=>1, 
            'cpf_cnpj'=>'428.338.578-61', 
            'data_nascimento'=>'1996-07-31', 
            'nome_mae'=> 'Estela',
            'nome_pai'=> 'Flavio', 
            'endereco_id' => 1,
            'status' => 1
        ]);
        $this->call(FornecedorSeed::class);
        $this->call(ProdutoSeed::class);
        $this->call(ProdutoFornecedorSeed::class);
    }
}
