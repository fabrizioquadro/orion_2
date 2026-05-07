<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Investidor;

class ApiZapSignController extends Controller
{
    protected static $token_api = "bcb495bf-b8d9-42db-b46e-b0e8f63c5d37384871b2-83e5-4de4-ab11-b1d2384b789e";
    protected static $array_search = ['nome_completo','email','telefone','cpf','cep','endereco','numero','complemento','bairro','cidade','uf','banco','tipo_conta','agencia','numero_conta','tipo_conta','pix','porcentagem_rendimento'];
    protected static $array_replace = ['nome','email','telefone','cpf','cep','endereco','numero','complemento','bairro','cidade','uf','banco','tipo_conta','agencia','nr_conta','tipo_conta','chave_pix','indice_rendimento'];
    protected static $array_object = ['user','user','user','investidor','investidor','investidor','investidor','investidor','investidor','investidor','investidor','investidor','investidor','investidor','investidor','investidor','investidor','investimento'];
    protected static $template_id = 'c90ba1a4-ac83-4be6-a0e7-533bcc17e4ae';
    protected static $api_create_doc = 'https://api.zapsign.com.br/api/v1/models/create-doc/';
    protected static $api_verifica_assinatura = "https://api.zapsign.com.br/api/v1/docs/?";

    public static function create_doc($investimento){
        try {
            /*
            $user = User::where('id', $investimento->user_id)->first();
            $investidor = Investidor::where('user_id', $investimento->user_id)->first();

            $dados = array();
            $contador = count(self::$array_search);
            for($i=0 ; $i<$contador ; $i++){
                $entidade = self::$array_object[$i];
                $entidade = $$entidade;
                $coluna = self::$array_replace[$i];
                $array = [
                    'de' => '{{'.self::$array_search[$i].'}}',
                    'para' => $entidade->$coluna,
                ];
                $dados[] = $array;
            }


            //vamos verificar a questão do endereço completo
            $endereco_completo = "$investidor->endereco, $investidor->numero $investidor->complemento $investidor->bairro $investidor->cidade/$investidor->uf";
            $array = [
                'de' => '{{endereco_completo}}',
                'para' => $endereco_completo,
            ];
            $dados[] = $array;

            //vamos verificar o valor extenso e o valor
            $array = [
                'de' => '{{valor_aporte}}',
                'para' => valorDbForm($investimento->vl_investimento),
            ];
            $dados[] = $array;

            $array = [
                'de' => '{{valor_extenso}}',
                'para' => valorPorExtenso($investimento->vl_investimento),
            ];
            $dados[] = $array;

            //vamos verificar a porcentagem extenso
            $array = [
                'de' => '{{porcentagem_extenso}}',
                'para' => numeroPorExtenso($investimento->indice_rendimento)." porcento",
            ];
            $dados[] = $array;

            //vamos berificar o valor ganho e  total
            $array = [
                'de' => '{{valor_rendimento}}',
                'para' => valorDbForm($investimento->vl_retorno_ganho),
            ];
            $dados[] = $array;

            $array = [
                'de' => '{{total}}',
                'para' => valorDbForm($investimento->vl_retorno_ganho + $investimento->vl_investimento),
            ];
            $dados[] = $array;

            //dia mes e ano
            $var = explode('-', $investimento->dt_investimento);
            $array_mes = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
            $ano = $var[0];
            $mes = $array_mes[(int)$var[1] - 1];
            $dia = $var[2];

            $array = [
                'de' => '{{dia}}',
                'para' => $dia,
            ];
            $dados[] = $array;

            $array = [
                'de' => '{{mes}}',
                'para' => $mes,
            ];
            $dados[] = $array;

            $array = [
                'de' => '{{ano}}',
                'para' => $ano,
            ];
            $dados[] = $array;

            $parametros = [
                'template_id' => self::$template_id,
                'signer_name' => $user->nome,
                'send_automatic_email' => true,
                'send_automatic_whatsapp' => false,
                'lang' => 'pt-br',
                'external_id' => $user->id,
                'data' => $dados,
            ];

            $ch = curl_init(self::$api_create_doc);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parametros));

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer ".self::$token_api,
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            if (curl_errno($ch)) {
                return false;
            } else {
                $array_retorno = json_decode($response);
            }

            if($array_retorno->signers[0]->token){
                //Investidor::where('user_id', $investidor->user_id)->update(['token_doc_zap_sign' => $array_retorno->signers[0]->token]);
                $investimento->token_doc_zap_sign = $array_retorno->signers[0]->token;
                $investimento->save();
                return true;
            }
            else{
                return false;
            }
            */

            $investimento->assinatura_contrato_investimento = 'Sim';
            $investimento->save();
            return true;

        } catch (\Exception $e) {
            //dd($e->getMessage());
            return false;
        }
    }


    public static function verifica_assinatura($investimento){
        try {
            $retorno = "Sim";
            if($investimento->assinatura_contrato_investimento != "Sim"){
                $retorno = "Não";
                if($investimento->token_doc_zap_sign){
                    $parametros = [
                        'doc_token' => $investimento->token_doc_zap_sign,
                    ];

                    //$apiUrl = self::$api_verifica_assinatura.http_build_query($parametros);
                    $apiUrl = "https://api.zapsign.com.br/api/v1/docs/$investimento->token_doc_zap_sign/";

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $apiUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    // Define explicitamente o método GET (opcional)
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        "Authorization: Bearer ".self::$token_api
                    ]);

                    $response = curl_exec($ch);
                    curl_close($ch);

                    $array_retorno = json_decode($response);

                    if($array_retorno->results[0]->status == 'signed'){
                        $retorno = 'Sim';
                        $investimento->assinatura_contrato_investimento = 'Sim';
                    }
                }
            }
            return $retorno;
        }catch (\Exception $e) {
            //dd($e->getMessage());
        }
    }

    public static function get_modelos(){
        try {
            $apiUrl = 'https://api.zapsign.com.br/api/v1/templates/';
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Define explicitamente o método GET (opcional)
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer ".self::$token_api
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $array_retorno = json_decode($response);
            echo "aqui";
            echo "<pre>";
            print_r($response);
            echo "</pre>";
        }catch (\Exception $e) {

        }

    }
}
