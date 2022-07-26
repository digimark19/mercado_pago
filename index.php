<?php 
// SDK de Mercado Pago
require __DIR__ .  '/vendor/autoload.php';

// MercadoPago\SDK::setAccessToken('TEST-6196813918475187-062612-3841e0096245caf473519eb7ff6674f0-252204241');
MercadoPago\SDK::setAccessToken('TEST-7756684308165475-071912-f10fa977f33f99dffbc360b0926c9438-1163486988');

// Crea un objeto de preferencia
$preference = new MercadoPago\Preference();

//CONFIGURACION DE BACK_URL PARA QUE AL FINALIZAR ME REGRESE A LA RUTA ESPECIFICADA
$preference->back_urls = array(
    "success" => "http://localhost:8080/componentes/sdkMercadoPago/pagoexitoso.php",
    "failure" => "http://localhost:8080/componentes/sdkMercadoPago/pagofallido.php",
    "pending" => "http://localhost:8080/componentes/sdkMercadoPago/pagopendiente.php"
);
//Redirecciona en automatico desdepues de ser aprovado
$preference->auto_return = "approved";

$preference->name = "JESUS";
$preference->surname = "TESTWOC5R05W";
$preference->email = "test_user_11948090@testuser.com";
$preference->date_created = "2018-06-02T12:58:41.425-04:00";

$preference->phone = array(
    "area_code" => "",
    "number" => "949 128 866"
  );
  
  $preference->address = array(
    "street_name" => "Cuesta Miguel Armendáriz",
    "street_number" => 1004,
    "zip_code" => "11020"
  );
// Crea un ítem en la preferencia
$datos = array();

for ($i=0; $i < 5; $i++) { 
    $item = new MercadoPago\Item();
    $item->title = 'pantalon';
    $item->quantity = 1;
    $item->unit_price = 75.56;
    $item->currency_id = "MXN";
    $item->description = "Table is made of heavy duty white plastic and is 96 inches wide and 29 inches tall";
    $item->category_id = "otros";
    $datos[] = $item;
}
$preference->items = $datos;
$preference->save();
// echo $preference->id; 
// print_r($preference);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script headerColor="jqeury.js"></script>
    <title>Document</title>
</head>
<body>
    <!-- checkout pro - UI ya lista y nos manda a la web de mercado pago-->
    <div class="checkoutpro">
        <H1>CHECKOUT PRO </H1>

        <textarea name="" id="" cols="30" rows="10">
            <?php 
                print_r($preference);
            ?>
        </textarea>
        <button class="cho-container"></button>
        <a target="_blank" href="<?php echo $preference->init_point; ?>">Pagar con Mercado Pago</a>
    </div>
    <!-- checkout pro - UI ya lista y nos manda a la web de mercado pago-->
    
    <!-- checkout API - semi UI modulos de la forma de pago prediseñados y los mando a llamar cuando se necesite -->
    <div>
        <H1>CHECKOUT API BRICKS</H1>
        <div id="cardPaymentBrick_container"></div>
    </div>
    <!-- checkout API - semi UI modulos de la forma de pago prediseñados y los mando a llamar cuando se necesite -->
   
   <script>
    //CHECKOUT PRO
        // Agrega credenciales de SDK
        const mp = new MercadoPago("TEST-2073a79c-e9d1-4344-8c01-645378355ade", {
            locale: "es-MX",
        });

        // Inicializa el checkout
        mp.checkout({
            preference: {
            id: "<?php echo $preference->id; ?>",
            },
            render: {
            container: ".cho-container", // Indica el nombre de la clase donde se mostrará el botón de pago
            label: "Pagar", // Cambia el texto del botón de pago (opcional)
            },
            theme: {
            elementsColor: '#02ff1b',
            headerColor: '#02ff1b'
        }
            // autoOpen: true,
        });
    //CHECKOUT PRO

    //CHECKOUT API BRICK
        const bricksBuilder = mp.bricks();
        const renderCardPaymentBrick = async (bricksBuilder) => {

        const settings = {
        initialization: {
            amount: 100, //valor del pago a realizar
        },
        callbacks: {
            onReady: () => {
            // callback llamado cuando Brick esté listo
            },
            onSubmit: (cardFormData) => {
            // callback llamado cuando el usuario haga clic en el botón enviar los datos

            // ejemplo de envío de los datos recolectados por el Brick a su servidor
            return new Promise((resolve, reject) => {
                fetch("https://prodev.budget.com.mx/mercadopago", { 
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(cardFormData)
                })
                .then((response) => {
                    // recibir el resultado del pago
                    resolve();
                })
                .catch((error) => {
                    // tratar respuesta de error al intentar crear el pago
                    reject();
                })
                });
            },
            onError: (error) => { 
            // callback llamado para todos los casos de error de Brick
            },
        },
        };
        const cardPaymentBrickController = await bricksBuilder.create('cardPayment', 'cardPaymentBrick_container', settings);
        };
        renderCardPaymentBrick(bricksBuilder);
    //CHECKOUT API BRICK






//     var userdatacliente = {
//         "id": 1163486988,
//         "nickname": "TETE1384199",
//         "password": "qatest2099",
//         "site_status": "active",
//         "email": "test_user_92473667@testuser.com"
//     }
//     var userdatavendedor = {
//     "id": 1163526820,
//     "nickname": "TESTWOC5R05W",
//     "password": "qatest8836",
//     "site_status": "active",
//     "email": "test_user_11948090@testuser.com"
// }
    </script>
</body>
</html>