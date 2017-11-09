<?php  

//	$response = \GoogleMaps::load('geocoding')
//            ->setParam (['address' =>'santa cruz'])
//            ->get();
    
    //var_dump(json_decode($response));


//$response = \GoogleMaps::load('geocoding')
//		->setParam ([
//		    'address'    =>'santa cruz',
//         	    'components' => [
//                     	'administrative_area'  => 'TX',
//                     	'country'              => 'US',
//                      ]
//
//                ])
//                ->get();


//    dd($response);


//$response = \GoogleMaps::load('directions')
//           ->setParam([
//                'origin'          => 'place_id:EkFSLiBTZW4uIEFsYmVydG8gUGFzcXVhbGluZSAtIE1hcmlsYW5kLCBDYXhpYXMgZG8gU3VsIC0gUlMsIEJyYXNpbA', 
//                'destination'     => 'place_id:ChIJhVIJ5NS8HpURkOlxwF0-PVo', 
//            ])
//           ->containsLocation(55.86483,-4.25161);

//    dd( $response  ); 
//EkFSLiBTZW4uIEFsYmVydG8gUGFzcXVhbGluZSAtIE1hcmlsYW5kLCBDYXhpYXMgZG8gU3VsIC0gUlMsIEJyYXNpbA
//ChIJhVIJ5NS8HpURkOlxwF0-PVo


//$json = 'https://maps.googleapis.com/maps/api/directions/json?origin=Rua+Senador+Alberto+Pasqualini,1535+Caxias+do+Sul+RS&destination=Rua+hercules+Gallo,515+Caxias+do+Sul+RS&key=AIzaSyDJxctnzY4Jno5loThi8G5cRVmbwiOsIw0';

// link para retornar o json do google api.
//$post = file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=Rua+Senador+Alberto+Pasqualini,1535+Caxias+do+Sul+RS&destination=Rua+hercules+Gallo,515+Caxias+do+Sul+RS&key=AIzaSyDJxctnzY4Jno5loThi8G5cRVmbwiOsIw0");
//$json = json_dencode($post);

//&origin=Rua+hercules+Gallo,515+Caxias+do+Sul+RS&destination=Rua+Senador+Alberto+Pasqualini,1535+Caxias+do+Sul+RS&avoid=tolls|highways" 



?>




<h3 id="modo_directions">Modo Directions</h3>
<p>O modo <code>Directions</code> exibe o caminho entre dois ou mais pontos especificados
no mapa, além da distância e do tempo de percurso.</p>
<iframe class="map-top"
    width="1000"
    height="700"
    src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyDJxctnzY4Jno5loThi8G5cRVmbwiOsIw0
    &origin=-29.16312226, -51.17977472&destination=-29.16117, -51.18444&avoid=tolls|highways" 
    allowfullscreen>
</iframe>




