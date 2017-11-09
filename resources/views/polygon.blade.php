<?php  

$response = \GeometryLibrary\PolyUtil::containsLocation(
			        //['lat' => -29.1128715, 'lng' => -51.1251388], //ponto -x Lanches
			        ['lat' => -29.120589, 'lng' => -51.1333034], //Maqsul Industria de Maquinas.

              //['lat' => -29.116774808722834, 'lng' => -51.12934112548828], // point array [lat, lng] Pressionado dentro do polygono.

              [ // poligon arrays of [lat, lng]
               ['lat' => -29.1203365, 'lng' => -51.1348772], 
		           ['lat' => -29.1208051, 'lng' => -51.1312938], 
		           ['lat' => -29.1202053, 'lng' => -51.1304784], 
     	 	       ['lat' => -29.1204865, 'lng' => -51.1270237], 
		 	         ['lat' => -29.1205427, 'lng' => -51.1251354], 
         	     ['lat' => -29.118012,  'lng' => -51.1251998], 
         	     ['lat' => -29.1161562, 'lng' => -51.1260796], 
         	     ['lat' => -29.1136254, 'lng' => -51.1252213], 
         	     ['lat' => -29.1126131, 'lng' => -51.124835],  
         	     ['lat' => -29.1110571, 'lng' => -51.1250067], 
         	     ['lat' => -29.1112727, 'lng' => -51.1333644], 
         	     ['lat' => -29.1112492, 'lng' => -51.1361378],
         	     ['lat' => -29.1134121, 'lng' => -51.1366662], 
         	     ['lat' => -29.1132563, 'lng' => -51.1348705],
         	     ['lat' => -29.1195895, 'lng' => -51.1347022],
         	     ['lat' => -29.1203365, 'lng' => -51.1348772], 
              ]);  
             
//dd($response); // false  

echo "<h1>Distancia e tempo</h1>";


function get_coordinates($city, $street, $province)
{
    $address = urlencode($city.','.$street.','.$province);
    $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=Poland";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response);
    $status = $response_a->status;

    if ( $status == 'ZERO_RESULTS' )
    {
        return FALSE;
    }
    else
    {
        $return = array('lat' => $response_a->results[0]->geometry->location->lat, 'long' => $long = $response_a->results[0]->geometry->location->lng);
        return $return;
    }
}

function GetDrivingDistance($lat1, $lat2, $long1, $long2)
{
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response, true);
    $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];

    return array('distance' => $dist, 'time' => $time);
}


//$coordinates1 = get_coordinates('Tychy', 'Jana Pawła II', 'Śląskie');
//$coordinates2 = get_coordinates('Lędziny', 'Lędzińska', 'Śląskie');

//if ( !$coordinates1 || !$coordinates2 )
//{
    //echo 'Bad address.';
//}
//else
//{
    //$dist = GetDrivingDistance($coordinates1['lat'], $coordinates2['lat'], $coordinates1['long'], $coordinates2['long']);
    //echo 'Distance: <b>'.$dist['distance'].'</b><br>Travel time duration: <b>'.$dist['time'].'</b>';
//}

$dist = GetDrivingDistance(-29.134245, -29.162660, -51.136810, -51.177483);
    echo 'Distance: <b>'.$dist['distance'].'</b><br>Travel time duration: <b>'.$dist['time'].'</b>';

echo "<br />";
echo "<hr></hr>";

//{------------------------------------------------------------------------------------------------------------------}


      $arq_kml = asset('xml/mz_aa001.kml'); 

      if (!file_exists(public_path('xml/mz_aa001.kml'))) {
        exit("arquivo não existe: ".$arq_kml);
      }

      $doc = new DOMDocument();
      $doc->load($arq_kml);

      $node_name = $doc->getElementsByTagName("name");
      $node_description = $doc->getElementsByTagName("description");

      // Limpara a variável
      $json = array();

      $json['name'] = $node_name->item(0)->nodeValue;

      // Pode não existir esta tag (descrição é opcional)
      if(isset($node_description->item(0)->nodeValue)) {
        $json['description'] = $node_description->item(0)->nodeValue;
      }

      // Pegamos todos os elementos "<Folder>"
      $folderNode = $doc->getElementsByTagName("Folder");
      $folderCount = 0;

      // Percorremos os elementos de cada Folder para pegarmos os 
      // "<Placemarks>" (marcações do mapa)
      foreach( $folderNode as $searchFolder ) 
      { 
        $tagNameFolder = $searchFolder->getElementsByTagName("name");
        $json['folder'][$folderCount]['name'] = $tagNameFolder->item(0)->nodeValue;

        $placemarkNode = $searchFolder->getElementsByTagName("Placemark"); 

        $placeCount = 0;

        foreach( $placemarkNode as $searchPlacemark ) 
        {    

          // processa polígonos 
          $polygonNode = $searchPlacemark->getElementsByTagName("Polygon");  
          
          // somente gravamos o placemark se existir um polígono, se for uma linha, será desconsiderado
          if (isset($polygonNode->item(0)->nodeValue)) {       
            
            $tagNamePlace = $searchPlacemark->getElementsByTagName("name");
            $tagDescriptionPlace = $searchPlacemark->getElementsByTagName("description");

            $json['folder'][$folderCount]['placemark'][$placeCount]['name'] = $tagNamePlace->item(0)->nodeValue;

            // Pode não existir esta tag (descrição é opcional)
            if (isset($tagDescriptionPlace->item(0)->nodeValue)) {
              $json['folder'][$folderCount]['placemark'][$placeCount]['description'] = $tagDescriptionPlace->item(0)->nodeValue;
            }

          }          

          $coordsCount = 0;

          // o "foreach" já se responsabiliza por verificar se existe um polígono, desta forma
          // não precisamos passar toda a codificação para o if acima (if (isset($polygonNode->item(0)->nodeValue)))
          foreach( $polygonNode as $searchPolygon ) 
          {
            $outerboundNode = $searchPolygon->getElementsByTagName("outerBoundaryIs");

            foreach( $outerboundNode as $searchOuterbound )               
            {
              $linearRingNode = $searchOuterbound->getElementsByTagName("LinearRing");  

              foreach( $linearRingNode as $searchLinearRing ) 
              {
                $tagNameCoords = $searchLinearRing->getElementsByTagName("coordinates");
                $arrayCoords[] = $tagNameCoords->item(0)->nodeValue;

                $json['folder'][$folderCount]['placemark'][$placeCount]['coordinates'][$coordsCount] = $tagNameCoords->item(0)->nodeValue;
                $coordsCount++; 
              }

            }
        
          }

          $placeCount++;

        }

        $folderCount++;

      } 


foreach ($json['folder'] as $key => $value) {

        echo $folderName = $value['name'];        
        
}



echo "<h1>Formato JSON</h1>";
echo "<br />";

echo json_encode($json);

echo "<hr></hr>";


echo "<h1>Formato ARRAY</h1>";
echo "<br />";

$json2 = '{"nome":"mz_aa001","descricao":"Mapa Motozapp - \u00c1rea Abrang\u00eancia 001 - Caxias do Sul.","tipo":[{"nome":"zona_orig","chave":[{"nome":"\u00c1rea S\u00e3o Ciro II","descricao":"Bairro de coleta.","coords":["\n -51.1405635,-29.1262411,0\n -51.1401586,-29.1271057,0\n -51.1384661,-29.1283076,0\n -51.1382998,-29.1314237,0\n -51.1381389,-29.1328201,0\n -51.137259,-29.1339635,0\n -51.1370444,-29.1346195,0\n -51.1370015,-29.1353786,0\n -51.1370874,-29.1369576,0\n -51.1364436,-29.1359502,0\n -51.1365509,-29.1339634,0\n -51.1375273,-29.1325389,0\n -51.1377821,-29.1316673,0\n -51.1378652,-29.1297836,0\n -51.1379484,-29.1280686,0\n -51.1396207,-29.12663,0\n -51.138118,-29.1269042,0\n -51.1372591,-29.1271034,0\n -51.137774,-29.1260162,0\n -51.1395765,-29.1259975,0\n -51.1405635,-29.1262411,0\n "]}]},{"nome":"zona_dest","chave":[{"nome":"\u00c1rea Serrano","descricao":"Bairro de entrega.","coords":["\n -51.1377525,-29.1111508,0\n -51.1373234,-29.1215737,0\n -51.1241913,-29.1214237,0\n -51.1239338,-29.1180495,0\n -51.1131191,-29.1176746,0\n -51.1136341,-29.1027518,0\n -51.121788,-29.1032018,0\n -51.1223888,-29.0958521,0\n -51.1321735,-29.0960771,0\n -51.1318302,-29.1107759,0\n -51.1377525,-29.1111508,0\n "]}]},{"nome":"zona_bloq","chave":[{"nome":"\u00c1rea Serrano b01","descricao":"\u00c1reas n\u00e3o seguras.","coords":["\n -51.1297558,-29.1181305,0\n -51.1325378,-29.1169913,0\n -51.133976,-29.1194742,0\n -51.1297558,-29.1181305,0\n ","\n -51.1325378,-29.1169913,0\n -51.1320126,-29.1160846,0\n -51.1352634,-29.115875,0\n -51.1325378,-29.1169913,0\n ","\n -51.1320126,-29.1160846,0\n -51.1286694,-29.1163001,0\n -51.1303711,-29.1132505,0\n -51.1320126,-29.1160846,0\n ","\n -51.127966,-29.1175606,0\n -51.1297558,-29.1181305,0\n -51.1270237,-29.1192493,0\n -51.127966,-29.1175606,0\n ","\n -51.127966,-29.1175606,0\n -51.1247921,-29.1165499,0\n -51.1286694,-29.1163001,0\n -51.127966,-29.1175606,0\n "]},{"nome":"\u00c1reas Serrano b02","descricao":"\u00c1reas n\u00e3o seguras.","coords":["\n -51.1229038,-29.1109259,0\n -51.1229897,-29.1141503,0\n -51.1194706,-29.1140753,0\n -51.1191273,-29.1108509,0\n -51.1229038,-29.1109259,0\n "]}]}]}';

$obj = json_decode($json2);

print_r($obj);

echo "<hr></hr>";

echo "<h1>Array de coordenadas</h1>";
echo "<br />";

echo $obj->tipo[0]->chave[0]->coords[0];

echo "<hr></hr>";

echo "<h1>Array de coordenadas separadas</h1>";
echo "<br />";

function after ($this, $inthat)
{
  if (!is_bool(strpos($inthat, $this)))
    return substr($inthat, strpos($inthat,$this)+strlen($this));
};

function before ($this, $inthat)
{
  return substr($inthat, 0, strpos($inthat, $this));
};    

$coords = explode(",0", $obj->tipo[0]->chave[0]->coords[0]);        
array_pop($coords); //remove o ultimo elemento do array (o explode está deixando um espaço em branco como ultimo elemento.).

foreach ($coords as $key) {
  echo "lng: ".trim(before(',', $key))." | "."lat: ".trim(after(',', $key));
  echo "<br />";   
}

?>



