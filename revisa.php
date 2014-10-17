<?php
/*
Clase con una función estática que dada por parámetro $ci una cédula de alguien registrado en el CNE 
retorna en un Json los datos del CNE

Ejemplo de uso:

$cne = functions::getDatosCne("8370825");
header('Content-type: application/json');
echo $cne;

Debe retornar: 

{"cedula":"V-8370825","nombre":"DIOSDADO  CABELLO RONDON","estado":"EDO. MONAGAS","municipio":"CE. MATURIN","parroquia":"PQ. EL FURRIAL","centro":"ESCUELA B\u00c1SICA RAFAEL MARIA BARALTDirecci\u00f3n:SECTOR EL FURRIAL IZQUIERDA CALLE 3 POR LA CANCHA. FRENTE CALLE JOSE MARIA BARAL. DERECHA TRANSVERSAL 1 CALLE LOS ROSALES CASERIO EL FURRIAL EDIFICIO"}

Idea basada en el código visto en http://roy-rc.blogspot.com/2013/03/curl-php.html

*/

class functions {
public static function getDatosCne($ci){
        $url="http://www.cne.gov.ve/web/registro_electoral/ce.php?nacionalidad=V&cedula=$ci";
        $curl = curl_init();
        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $cne='';
        if(curl_exec($curl) === false){
            echo 'Curl error: ' . curl_error($curl);
        }else
        {
            $cne = curl_exec($curl);
            curl_close($curl);
            $inicio = stripos($cne, '<table cellpadding="2" width="530">',0)+strlen('<table cellpadding="2" width="530">');
            if($inicio){
                $cne_datos = substr($cne,$inicio);
                /*
                    Aqui borro los datos del encabezado de retorno de la peticion curl
                */
                $cne = substr($cne_datos,0, stripos($cne_datos, '</table>',0));
                $cne = strip_tags(htmlspecialchars_decode($cne));
                /*
                    Elimino lineas nuevas y tabulaciones
                */
                $cne = str_replace( "\t","" ,$cne);
                $cne = str_replace( "\n","" ,$cne);
                $persona["cedula"] = trim(substr($cne, stripos($cne, "dula:")+5    ,(stripos($cne, "Nombre:")- stripos($cne, "dula:")-5))) ;
                $persona["nombre"] = trim(substr($cne, stripos($cne, "mbre:")+5    ,(stripos($cne, "Estado:")- stripos($cne, "mbre:")-5)));
                $persona["estado"] = trim(substr($cne, stripos($cne, "tado:")+5    ,(stripos($cne, "Municipio:")- stripos($cne, "tado:")-5)));
                $persona["municipio"] = trim(substr($cne, stripos($cne, "ipio:")+5 ,(stripos($cne, "Parroquia:")- stripos($cne, "ipio:")-5)));
                $persona["parroquia"] = trim(substr($cne, stripos($cne, "quia:")+5 ,(stripos($cne, "Centro:")- stripos($cne, "quia:")-5)));
                $persona["centro"]    = trim(substr($cne, stripos($cne, "ntro:")+5    ,(strlen($cne)  - stripos($cne, "ntro:")-5)));
                $cne=json_encode($persona);
            }
            else{
                $cne = false;
            }
        }
        return $cne;
    }
}

$cne = functions::getDatosCne("8370825");
header('Content-type: application/json');
echo $cne;

?>