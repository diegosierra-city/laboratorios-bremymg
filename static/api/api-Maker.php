<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Expose-Headers: Content-Length, X-JSON");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Accept, Accept-Language, X-Authorization");
header('Access-Control-Max-Age: 86400');


error_reporting(E_ALL);

if (isset($_GET['error'])) {
  ini_set('display_errors', '1');
} else {
  ini_set('display_errors', '0');
}

//echo date('Y-m-d H:i:s');

$db_host = "localhost";
$db_user = "sysamericacolomb_user";
$db_pass = "q58dVa((-VcB";
$db_name = "sysamericacolomb_maker";

//
$conn = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);
mysqli_query($conn, "SET CHARACTER SET 'utf8'");

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");

$hoy = date('Y-m-d');

$keyEncrypter = '-Hy1jFr6+';



function clean_link($texto)
{
  $texto = trim($texto);
  $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "Ñ", " ");
  $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "-");
  $texto = str_replace($no_permitidas, $permitidas, $texto);
  return $texto;
}

function clean_numbers($numero)
{
  $no_permitidas = array(".", "-");
  $permitidas = array("", "");
  $numero = str_replace($no_permitidas, $permitidas, $numero);
  return $numero;
}


$data = json_decode(file_get_contents('php://input'), true);

/*
 $a=0;
foreach($data as $campo => $valor){
	$a++;
echo 	$a.' '.$campo.'= '.$valor.'<br>';
}
//return;
*/

////------------------


////-------
$ref = $_GET['ref'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  if ($ref == 'test') {
    ////+++++
    $empresa_id = $_GET['empresa_id'];
    $registros = array();


    header("HTTP/1.1 200 OK");
    echo '{"nombre":"Diego"}';
    //////++++

  } else 
if ($ref == 'loadID') {

    $user_id = $_GET['user_id'];
    $time = $_GET['time'];
    $token = $_GET['token'];

    $tokenB = md5($user_id . $time . $keyEncrypter);

    if ($token != $tokenB) {
      header("HTTP/1.1 403 ERROR");
      echo 'Error in token*';
    } else {
      $folder = $_GET['folder'];
      $field = $_GET['field'];
      $id = $_GET['id'];

      if ($folder == 'maker_content') {
        $folder = 'maker_content_blocks';
        $field = 'menu_id';
      }

      ///company_id
      $rCi = $mysqli->query("SELECT company_id FROM maker_users WHERE id='$user_id' LIMIT 1 ") or die($mysqli->error);
      $rowCi = $rCi->fetch_array(MYSQLI_ASSOC);
      $company_id = $rowCi['company_id'];

      $field2 = ",company_id";
      $id2 = ",'$company_id'";
      $add = "AND company_id='$company_id' ";

      if ($folder == 'maker_companies') {
        $field = 'id';
        $id = $company_id;
        $field2 = '';
        $id2 = '';
        $add = '';
      }
      if ($folder == 'maker_content_blocks') {
        $field = 'menu_id';
      }


      //echo "SELECT * FROM $folder WHERE $field='$id' LIMIT 1 <br>";
      $response = array();
      $result = $mysqli->query("SELECT * FROM $folder WHERE $field='$id' $add LIMIT 1") or die($mysqli->error);

      if (mysqli_num_rows($result) == 0) {

        $mysqli->query("INSERT INTO $folder ($field $field2) VALUES ('$id' $id2)") or die($mysqli->error);
        $result = $mysqli->query("SELECT * FROM $folder WHERE $field='$id' LIMIT 1") or die($mysqli->error);
      }
      $row = $result->fetch_array(MYSQLI_ASSOC);

      $response = array();


      if ($folder != 'maker_content_blocks') {
        $response = $row;
      } else {
        /// type
        $rTy = $mysqli->query("SELECT `type` FROM maker_menu WHERE id='$id' LIMIT 1 ");
        $rowTy = $rTy->fetch_array(MYSQLI_ASSOC);
        $type = $rowTy['type'];

        $response[] = $row;
        ///
        $form = array();
        $gallery = array();
        $menu_id = $row['id'];
        if ($type == 'Form') {
          $result = $mysqli->query("SELECT * FROM maker_form WHERE menu_id='$menu_id' ORDER BY position ASC ");
          $f = 0;

          while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $f++;

            if ($row['required'] == 0) {
              $row['required'] = false;
            } else {
              $row['required'] = true;
            }

            $row['response'] = '';
            $form[] = $row;
          }

          if ($f == 0) {
            $form = '[{}]';
          }
          //

        } else if ($type == 'Gallery') {
          $resultG = $mysqli->query("SELECT * FROM maker_gallery WHERE content_id='$menu_id' ORDER BY position") or die($mysqli->error);
          while ($rowG = $resultG->fetch_array(MYSQLI_ASSOC)) {
            $gallery[] = $rowG;
          }
        }
        $response[] = $form;
        $response[] = $gallery;
      }




      header("HTTP/1.1 200 OK");
      echo json_encode($response);
    }
  }
  if ($ref == 'loadIDWeb') {

    $company_id = $_GET['company_id'];
    $tokenWeb = $_GET['tokenWeb'];

    $tokenB = md5($company_id . $keyEncrypter);

    if ($tokenWeb != $tokenB) {
      header("HTTP/1.1 403 ERROR");
      echo 'Error in token';
    } else {
      $folder = $_GET['folder'];
      $field = $_GET['field'];
      $name = $_GET['name'];
      $id = $_GET['id'];

      //echo "SELECT * FROM $folder WHERE $field='$id' LIMIT 1 <br>";
      $response = array();
      if ($_GET['id']) {
        $result = $mysqli->query("SELECT * FROM $folder WHERE id='$id' LIMIT 1");
      } else {
        $result = $mysqli->query("SELECT * FROM $folder WHERE REPLACE($field, ' ', '-')='$name' LIMIT 1") or die($mysqli->error);
        //echo "SELECT * FROM $folder WHERE REPLACE($field, ' ', '-')='$name' LIMIT 1";
      }

      $row = $result->fetch_array(MYSQLI_ASSOC);
      $response[] = $row;

      if ($folder == 'maker_products') {
        $category_id = $row['category_id'];
        ///cargamos la imagen de la categoria
        $resultC = $mysqli->query("SELECT image FROM maker_categories WHERE id='$category_id' LIMIT 1");
        $rowC = $resultC->fetch_array(MYSQLI_ASSOC);
        $response[] = $rowC;
      }

      header("HTTP/1.1 200 OK");
      echo json_encode($response);
    }
  } else
  if ($ref == 'loadNameWeb') {

    $company_id = $_GET['company_id'];
    $tokenWeb = $_GET['tokenWeb'];
    $tokenB = md5($company_id . 'Fr-96(');

    if ($tokenWeb != $tokenB) {
      header("HTTP/1.1 202 ERROR");
      echo 'Error in token';
    } else {
      $folder = $_GET['f'];
      $name = $_GET['name'];
      $response = array();

      if ($folder == 'products') {
        //1

        $category_id = 0;
        $category_name = '';
        $resultC = $mysqli->query("SELECT id, category FROM categories WHERE company_id='$company_id' ");
        while ($rowC = $resultC->fetch_array(MYSQLI_ASSOC)) {
          if (clean_link($rowC['category']) == $name) {
            $category_id = $rowC['id'];
            $category_name = $rowC['category'];
          }
        }

        $resultP = $mysqli->query("SELECT * FROM products WHERE category_id='$category_id' AND active='1' ORDER BY position");
        while ($rowP = $resultP->fetch_array(MYSQLI_ASSOC)) {
          $rowP['category_name'] = $category_name;
          $response[] = $rowP;
        }
      } else if ($folder == 'product') {
        //1
        $resultP = $mysqli->query("SELECT products.*, categories.category AS category_name FROM categories, products WHERE categories.company_id='$company_id' AND categories.active='1' AND products.category_id=categories.id ORDER BY products.position");
        while ($rowP = $resultP->fetch_array(MYSQLI_ASSOC)) {
          //echo $rowP['product'].'*';
          if (clean_link($rowP['product']) == $name) {
            $response[] = $rowP;
            break;
          }
        }
      }
      //


      header("HTTP/1.1 200 OK");
      //echo '[{"category_name":"'.$category_name.'"}],';
      echo json_encode($response);
    }
  } else
  if ($ref == 'listWeb') {

    $company_id = $_GET['company_id'];
    $tokenWeb = $_GET['tokenWeb'];
    $tokenB = md5($company_id . $keyEncrypter);

    if ($tokenWeb != $tokenB) {
      header("HTTP/1.1 202 ERROR");
      echo 'Error in token';
    } else {
      $folder = $_GET['f'];
      $response = array();

      if ($folder == 'options') {
        $product_id = $_GET['id'];
        $result = $mysqli->query("SELECT * FROM product_options WHERE product_id='$product_id' AND active='1' ORDER BY position");
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          $response[] = $row;
        }
      }else if ($folder == 'AccessCategories') {
        
        $result = $mysqli->query("SELECT p.*, c.category AS nombre_categoria
        FROM (
            SELECT DISTINCT category_id
            FROM maker_products
        ) AS categorias_distintas
        JOIN maker_products p ON categorias_distintas.category_id = p.category_id
        JOIN maker_categories c ON p.category_id = c.id
        WHERE p.company_id = '$company_id'
        GROUP BY c.id
        ORDER BY RAND()");
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          $response[] = $row;
        }
      } else {
        $result = $mysqli->query("SELECT * FROM $folder WHERE company_id='$company_id' AND active='1' ORDER BY position");
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          $response[] = $row;
        }
      }
      //




      header("HTTP/1.1 200 OK");
      echo json_encode($response);
    }
  } else 
  if ($ref == 'list') {
    $user_id = $_GET['user_id'];
    $time = $_GET['time'];
    $token = $_GET['token'];
    $tokenB = md5($user_id . $time . '-Hy1jFr6+');
    if ($token != $tokenB) {
      header("HTTP/1.1 202 ERROR");
      echo 'Error in token';
    } else {
      $id = $_GET['id'];
      $folder = $_GET['f'];


      if ($folder == 'maker_content_blocks') {
        $field = 'menu_id';
      } else if ($folder == 'maker_product_versions') {
        $folder = 'maker_product_versions';
        $field = 'product_id';
      }
      //echo "SELECT * FROM $folder WHERE $field='$id' LIMIT 1 <br>";
      $response = array();
      $result = $mysqli->query("SELECT * FROM $folder WHERE $field='$id' ORDER BY position") or die($mysqli->error);
      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $response[] = $row;
      }

      header("HTTP/1.1 200 OK");
      echo json_encode($response);
    }
  } else
    ///PMS
    if ($ref == 'load') {

      $user_id = $_GET['user_id'];
      $time = $_GET['time_life'];
      $token = $_GET['token'];

      $tokenBase = md5($user_id . $time . $keyEncrypter);

      if ($tokenBase != $token) {
        header("HTTP/1.1 403 ERROR");
        echo 'Error in token';
      } else {
        $id = $_GET['id'];
        $folder = $_GET['folder'];
        /// load categories
        $response = array();
        //echo "SELECT * FROM $folder WHERE id='$id' LIMIT 1 <br>";
        $result = $mysqli->query("SELECT * FROM $folder WHERE id='$id' LIMIT 1");
        $row = $result->fetch_array(MYSQLI_ASSOC);

        $response = $row;
        header("HTTP/1.1 200 OK");
        //echo $fecha.'*'.$empresa_id;
        echo json_encode($response);
      }
    } else if ($ref == 'load-list') {

      $company_id = $_GET['company_id'];
      $tokenWeb = $_GET['tokenWeb'];

      $tokenB = md5($company_id . $keyEncrypter);

      if ($tokenWeb != $tokenB) {
        header("HTTP/1.1 403 ERROR");
        echo 'Error in token:'.$tokenBase.'!='.$token; 
        //echo 'Error in token';
      } else {
        $company_id = $_GET['company_id'];
        $folder = $_GET['folder'];
        $type = $_GET['type'];
        $order = $_GET['order'];
        $campo = $_GET['campo'];
        $campoV = $_GET['campoV'];
        //
        $filtre = '';
        if ($type != '') {
          $filtre = "AND type='$type'";
        }
        if ($_GET['campo']) {
          $filtre .= "AND $campo='$campoV'";
        }
        if ($order == '') $order = 'position';

        if ($company_id == '') {
          $rCi = $mysqli->query("SELECT company_id FROM maker_users WHERE id='$user_id' LIMIT 1 ") or die($mysqli->error);
          $rowCi = $rCi->fetch_array(MYSQLI_ASSOC);
          $company_id = $rowCi['company_id'];
        }
        /// load categories
        $response = array();
        $response2 = array();
        //echo "SELECT * FROM maker_products WHERE category_id='$category_id' AND active='1' ORDER BY position ASC <br>";
        //echo "SELECT * FROM $folder WHERE company_id='$company_id' $filtre ORDER BY $order <br>";
        $result = $mysqli->query("SELECT * FROM $folder WHERE company_id='$company_id' $filtre ORDER BY $order") or die($mysqli->error);
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          /// 
          if ($folder == 'maker_orders') {
            $comprador_id = $row['comprador_id'];
            //
            $rCP = $mysqli->query("SELECT * FROM maker_buyer WHERE company_id='$company_id' AND id='$comprador_id' LIMIT 1") or die($mysqli->error);
            $rowCP = $rCP->fetch_array();
            $response2[] = $rowCP;
          }

          $response[] = $row;
        }

        $final = array();
        if (count($response2) > 0) {
          $final[] = $response;
          $final[] = $response2;
        } else {
          $final = $response;
        }

        header("HTTP/1.1 200 OK");
        //echo $fecha.'*'.$empresa_id;
        echo json_encode($final);
      }
    } else if ($ref == 'load-listGallery') {

      $user_id = $_GET['user_id'];
      $time = $_GET['time'];
      $token = $_GET['token'];

      $tokenBase = md5($user_id . $time . $keyEncrypter);

      if ($tokenBase != $token) {
        header("HTTP/1.1 403 ERROR");
        //echo 'Error in token:'.$tokenBase.'!='.$token; 
        echo 'Error in token';
      } else {
        $content_id = $_GET['id'];
        $folder = 'maker_gallery';
        //

        /// load categories
        $response = array();
        //echo "SELECT * FROM maker_products WHERE category_id='$category_id' AND active='1' ORDER BY position ASC <br>";
        //echo "SELECT * FROM $folder WHERE content_id='$content_id' ORDER BY position <br>";
        $result = $mysqli->query("SELECT * FROM $folder WHERE content_id='$content_id' ORDER BY position") or die($mysqli->error);
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          $response[] = $row;
        }


        header("HTTP/1.1 200 OK");
        //echo $fecha.'*'.$empresa_id;
        echo json_encode($response);
      }
    } else if ($ref == 'load-listGalleryWeb') {

      $company_id = $_GET['company_id'];
      $tokenWeb = $_GET['tokenWeb'];

      $tokenB = md5($company_id . $keyEncrypter);

      if ($tokenWeb != $tokenB) {
        header("HTTP/1.1 403 ERROR");
        echo 'Error in token';
      } else {
        $content_id = $_GET['id'];
        $folder = $_GET['folder'];

        if ($content_id > 0) {
          $folder = 'maker_gallery';
        }

        //

        /// load categories
        $response = array();
        //echo "SELECT * FROM maker_products WHERE category_id='$category_id' AND active='1' ORDER BY position ASC <br>";
        //echo "SELECT * FROM $folder WHERE content_id='$content_id' ORDER BY position <br>";
        //echo $content_id.'**<br>';
        if ($content_id > 0) {
          //echo $content_id.'++<br>';
          $result = $mysqli->query("SELECT * FROM $folder WHERE content_id='$content_id' ORDER BY position") or die($mysqli->error);
        } else {
          //echo $content_id.'--<br>';  
          /*
          id: number;
    content_id: number;
    image: string;
    title: string,
    description: string,
    position: number;
    */
          $result = $mysqli->query("SELECT id, image, category as title, position FROM $folder WHERE company_id='$company_id' ORDER BY position") or die($mysqli->error);
        }
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          $row['content_id'] = 0;
          $row['description'] = '';
          $row['linkURL'] = '/categoria/' . clean_link($row['title']);
          $response[] = $row;
        }


        header("HTTP/1.1 200 OK");
        //echo $fecha.'*'.$empresa_id;
        echo json_encode($response);
      }
    }else if ($ref == 'load-listCategorias') {

      $company_id = $_GET['company_id'];
      $tokenWeb = $_GET['tokenWeb'];

      $tokenB = md5($company_id . $keyEncrypter);

      if ($tokenWeb != $tokenB) {
        header("HTTP/1.1 403 ERROR");
        echo 'Error in token';
      } else {
        
        /// load categories
        $response = array();
                
          $result = $mysqli->query("SELECT id, category FROM maker_categories WHERE company_id='$company_id' AND active='1' ORDER BY position") or die($mysqli->error);
       
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
$category_id=$row['id'];
$resultP = $mysqli->query("SELECT * FROM maker_products WHERE company_id='$company_id' AND category_id='$category_id' AND active='1' ORDER BY position") or die($mysqli->error);
       
while ($rowP = $resultP->fetch_array(MYSQLI_ASSOC)) {
$rowP['linkURL'] = '/producto/' . clean_link($rowP['product']);  
$row['products'][] = $rowP;
  }

          $response[] = $row;
        }


        header("HTTP/1.1 200 OK");
        //echo $fecha.'*'.$empresa_id;
        echo json_encode($response);
      }
    } else if ($ref == 'load-listWeb') {

      $company_id = $_GET['company_id'];
      $tokenWeb = $_GET['tokenWeb'];

      $tokenB = md5($company_id . $keyEncrypter);

      if ($tokenWeb != $tokenB) {
        header("HTTP/1.1 403 ERROR");
        echo 'Error in token';
      } else {
        $folder = $_GET['folder'];
        $type = $_GET['type'];
        $campo = $_GET['campo'];
        $campoV = $_GET['campoV'];
        //
        $filtre = '';
        if ($type != '') {
          $filtre = "AND type='$type'";
        }
        if ($_GET['campo']) {
          $filtre .= "AND $campo='$campoV'";
        }
        /// load categories
        $response = array();
        //echo "SELECT * FROM $folder WHERE company_id='$company_id' $filtre ORDER BY position <br>";
        $result = $mysqli->query("SELECT * FROM $folder WHERE company_id='$company_id' $filtre ORDER BY position") or die($mysqli->error);
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          $response[] = $row;
        }


        header("HTTP/1.1 200 OK");
        //echo $fecha.'*'.$empresa_id;
        echo json_encode($response);
      }
    } else if ($ref == 'load-list-Web') {

      $company_id = $_GET['company_id'];
      $tokenWeb = $_GET['tokenWeb'];

      $tokenB = md5($company_id . $keyEncrypter);

      if ($tokenWeb != $tokenB) {
        header("HTTP/1.1 403 ERROR");
        echo 'Error in token';
      } else {
        $folder = $_GET['folder'];
        $name = $_GET['name'];
        /// load categories
        $response = array();
        $products = array();

        //echo "SELECT * FROM $folder WHERE company_id='$company_id' $filtre ORDER BY position <br>";
        $base_id = 0;
        if ($folder == 'maker_categories') {
          $category_id = 0;
          $image = '';
          $titulo = '';
          $subtitulo = '';

          $rC = $mysqli->query("SELECT id, category, description, `image` FROM maker_categories WHERE company_id='$company_id' ORDER BY position") or die($mysqli->error);
          while ($rowC = $rC->fetch_array(MYSQLI_ASSOC)) {
            if ($name == clean_link($rowC['category'])) {
              $base_id = $rowC['id'];
              $category_id = $rowC['id'];
              $image = $rowC['image'];
              $titulo = $rowC['category'];
              $subtitulo = $rowC['description'];
              break;
            }
          }

          $result = $mysqli->query("SELECT * FROM maker_products WHERE company_id='$company_id' AND category_id='$category_id' AND active='1' ORDER BY position") or die($mysqli->error);
          while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $row['folder'] = 'maker_products';
            $row['linkURL'] = 'producto/' . clean_link($row['titulo']);
            $opciones = array();
            //opciones
            $pId = $row['id'];
            $rOp = $mysqli->query("SELECT * FROM maker_product_versions WHERE company_id='$company_id' AND product_id='$pId' ORDER BY `name`") or die($mysqli->error);
            while ($rowOp = $rOp->fetch_array(MYSQLI_ASSOC)) {
              $opciones[] = $rowOp;
            }
            $row['variants'] = $opciones;
            /// fin opciones


            $products[] = $row;
          }
          //echo mysqli_num_rows($result).'++';
          if (mysqli_num_rows($result) == 0) {
            $products = '';
          }

          $response[] = $products;
          $response[] = $image;
          $response[] = $titulo;
          $response[] = $subtitulo;
          $response[] = $opciones;
          $response[] = $base_id;
        } else if ($folder == 'maker_products') {
          $category_id = 0;
          $image = '';
          $titulo = '';
          $subtitulo = '';

          $categories = array();
          $products = array();

          $rC = $mysqli->query("SELECT id, category, `description`, `image` FROM maker_categories WHERE company_id='$company_id' AND active='1' ORDER BY position") or die($mysqli->error);
          while ($rowC = $rC->fetch_array(MYSQLI_ASSOC)) {
            $categories[] = $rowC;
            $category_id = $rowC['id'];
            //
            $result = $mysqli->query("SELECT * FROM maker_products WHERE company_id='$company_id' AND category_id='$category_id' AND active='1' ORDER BY position") or die($mysqli->error);
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
              $row['folder'] = 'maker_products';
              $row['linkURL'] = 'producto/' . clean_link($row['titulo']);

              //opciones
              $pId = $row['id'];
              $rOp = $mysqli->query("SELECT * FROM maker_product_versions WHERE company_id='$company_id' AND product_id='$pId' ORDER BY `name`") or die($mysqli->error);
              while ($rowOp = $rOp->fetch_array(MYSQLI_ASSOC)) {
                $opciones[] = $rowOp;
              }
              $row['variants'] = $opciones;
              /// fin opciones

              $products[] = $row;
            }
            //echo mysqli_num_rows($result).'++';
            if (mysqli_num_rows($result) == 0) {
              $products = '';
            }
          }


          $response[] = $categories;
          $response[] = $products;
        }


        header("HTTP/1.1 200 OK");
        //echo $fecha.'*'.$empresa_id;
        echo json_encode($response);
      }
    } else if ($ref == 'load-listWebContent') {

      $company_id = $_GET['company_id'];
      $tokenWeb = $_GET['tokenWeb'];

      $tokenB = md5($company_id . $keyEncrypter);

      if ($tokenWeb != $tokenB) {
        header("HTTP/1.1 403 ERROR");
        echo 'Error in token';
      } else {
        $type = $_GET['type'];
        $name = $_GET['name'];
        //

        if ($folder != '') $filtre . " AND $folder.type='$type'";
        /// load categories
        $response = array();
        //echo "SELECT maker_menu.id, maker_menu.menu, maker_menu.type, maker_menu.metadescription, maker_menu.metakeywords, maker_content_blocks.id AS content_id, maker_content_blocks.title, maker_content_blocks.subtitle, maker_content_blocks.text1, maker_content_blocks.text2, maker_content_blocks.text3, maker_content_blocks.text4, maker_content_blocks.image1, maker_content_blocks.image2, maker_content_blocks.image3, maker_content_blocks.image4, maker_content_blocks.video, maker_content_blocks.position, maker_content_blocks.link FROM maker_menu, maker_content_blocks WHERE maker_menu.company_id='$company_id'  AND maker_content_blocks.menu_id=maker_menu.id AND maker_content_blocks.company_id='$company_id' GROUP BY maker_menu.id ORDER BY maker_menu.position";
        $result = $mysqli->query("SELECT maker_menu.id, maker_menu.menu, maker_menu.type, maker_menu.metadescription, maker_menu.metakeywords, maker_content_blocks.id AS content_id, maker_content_blocks.title, maker_content_blocks.subtitle, maker_content_blocks.text1, maker_content_blocks.text2, maker_content_blocks.text3, maker_content_blocks.text4, maker_content_blocks.image1, maker_content_blocks.image2, maker_content_blocks.image3, maker_content_blocks.image4, maker_content_blocks.video, maker_content_blocks.position, maker_content_blocks.link FROM maker_menu, maker_content_blocks WHERE maker_menu.company_id='$company_id'  AND maker_content_blocks.menu_id=maker_menu.id AND maker_content_blocks.company_id='$company_id' GROUP BY maker_menu.id ORDER BY maker_menu.position") or die($mysqli->error);
        //echo 'R:'.mysqli_num_rows($result).'++';
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          if ($name != '') {
            //echo clean_link($row['menu']) .'=='. $name .'*';
            if (clean_link($row['menu']) == $name) {
              // echo '*'.$row['id'];
              //$row['link'] = clean_link($row['menu']);
              $response[] = $row;
            }
          } else {
            $row['link'] = clean_link($row['menu']);
            $response[] = $row;
          }
        }


        header("HTTP/1.1 200 OK");
        //echo $fecha.'*'.$empresa_id;
        echo json_encode($response);
      }
    } else if ($ref == 'load-listDUO') {

      $company_id = $_GET['company_id'];
      $tokenWeb = $_GET['tokenWeb'];

      $tokenB = md5($company_id . $keyEncrypter);

      if ($tokeneb != $tokenB) {
        header("HTTP/1.1 403 ERROR");
        echo 'Error in token';
      } else {

        $folder = $_GET['folder'];
        $folderB = $_GET['folderB'];
        $union = $_GET['union'];
        /// load categories
        $response = array();
        //echo "SELECT * FROM maker_products WHERE category_id='$category_id' AND active='1' ORDER BY position ASC <br>";
        $result = $mysqli->query("SELECT $folderB.* FROM $folder, $folderB WHERE $folder.company_id='$company_id' AND $folderB.$union=$folder.id ORDER BY $folder.position ASC, $folderB.position ASC") or die($mysqli->error);
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          $response[] = $row;
        }


        header("HTTP/1.1 200 OK");
        //echo $fecha.'*'.$empresa_id;
        echo json_encode($response);
      }
    } else 
if ($ref == 'menu-list') {

      $user_id = $_GET['user_id'];
      $time = $_GET['time'];
      $token = $_GET['token'];

      $tokenB = md5($user_id . $time . $keyEncrypter);

      if ($token != $tokenB) {
        header("HTTP/1.1 403 ERROR");
        echo 'Error in token';
      } else {
        ///company_id
        $rCi = $mysqli->query("SELECT company_id FROM maker_users WHERE id='$user_id' LIMIT 1 ") or die($mysqli->error);
        $rowCi = $rCi->fetch_array(MYSQLI_ASSOC);
        $company_id = $rowCi['company_id'];

        $menu = array();
        // darle un resultado a un campo 
        //$result = $mysqli->query("SELECT *, IF(head, 'true', 'false') AS head, IF(foot, 'true', 'false') AS foot, IF(side, 'true', 'false') AS side, IF(submenu, 'true', 'false') AS submenu FROM maker_menu WHERE menu_id='0' ORDER BY position Asc ");
        $result = $mysqli->query("SELECT * FROM maker_menu WHERE menu_id='0' AND company_id='$company_id' ORDER BY position ASC ");
        $limit = mysqli_num_rows($result) + 1;
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

          if ($row['head'] == 0) {
            $row['head'] = false;
          } else {
            $row['head'] = true;
          }
          if ($row['foot'] == 0) {
            $row['foot'] = false;
          } else {
            $row['foot'] = true;
          }
          if ($row['side'] == 0) {
            $row['side'] = false;
          } else {
            $row['side'] = true;
          }
          if ($row['submenu'] == 0) {
            $row['submenu'] = false;
          } else {
            $row['submenu'] = true;
          }

          /// cargamos el submenu
          $m_id = $row['id'];
          $submenu = array();
          $resultSM = $mysqli->query("SELECT * FROM maker_menu WHERE menu_id='$m_id' ORDER BY position ASC ");
          $sb = 0;
          while ($rowSM = $resultSM->fetch_array(MYSQLI_ASSOC)) {
            $sb++;
            if ($rowSM['head'] == 0) {
              $rowSM['head'] = false;
            } else {
              $rowSM['head'] = true;
            }
            if ($rowSM['foot'] == 0) {
              $rowSM['foot'] = false;
            } else {
              $rowSM['foot'] = true;
            }
            if ($rowSM['side'] == 0) {
              $rowSM['side'] = false;
            } else {
              $rowSM['side'] = true;
            }
            if ($rowSM['submenu'] == 0) {
              $rowSM['submenu'] = false;
            } else {
              $rowSM['submenu'] = true;
            }
            $submenu[] = $rowSM;
          }

          $row['submenus'] = $submenu;


          $menu[] = $row;
        }


        ///
        header("HTTP/1.1 200 OK");
        //echo $fecha.'*'.$empresa_id;
        echo json_encode($menu);
      }
    } else 
if ($ref == 'form-list') {
      //echo 'xxxxx';
      $user_id = $_GET['user_id'];
      $time = $_GET['time'];
      $token = $_GET['token'];
      $menu_id = $_GET['menu_id'];

      $tokenB = md5($user_id . $time . $keyEncrypter);

      if ($token != $tokenB) {
        header("HTTP/1.1 403 ERROR");
        echo 'Error in token' . $menu_id;
      } else {
        $form = array();

        $result = $mysqli->query("SELECT * FROM maker_form WHERE menu_id='$menu_id' ORDER BY position ASC ");
        $f = 0;

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          $f++;

          if ($row['required'] == 0) {
            $row['required'] = false;
          } else {
            $row['required'] = true;
          }

          $row['response'] = '';
          $form[] = $row;
        }

        if ($f == 0) {
          //$form = '[{}]';
        }

        ///
        header("HTTP/1.1 200 OK");
        //echo $fecha.'*'.$empresa_id;
        echo json_encode($form);
      }
    } else 
if ($ref == 'form-listWeb') {
      $company_id = $_GET['company_id'];
      $tokenWeb = $_GET['tokenWeb'];

      $tokenB = md5($company_id . $keyEncrypter);

      if ($tokenWeb != $tokenB) {
        header("HTTP/1.1 403 ERROR");
        echo 'Error in token';
      } else {
        $menu_id = $_GET['id'];
        $form = array();

        $result = $mysqli->query("SELECT * FROM maker_form WHERE menu_id='$menu_id' ORDER BY position ASC ");
        $f = 0;

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          $f++;

          if ($row['required'] == 0) {
            $row['required'] = false;
          } else {
            $row['required'] = true;
          }

          $row['response'] = '';
          $form[] = $row;
        }

        if ($f == 0) {
          $form = '[{}]';
        }

        ///
        header("HTTP/1.1 200 OK");
        //echo $fecha.'*'.$empresa_id;
        echo json_encode($form);
      }
    } else 
if ($ref == 'form-list-report') {
      //echo 'xxxxx';
      $user_id = $_GET['user_id'];
      $time = $_GET['time'];
      $token = $_GET['token'];

      $tokenB = md5($user_id . $time . $keyEncrypter);

      if ($token != $tokenB) {
        header("HTTP/1.1 403 ERROR");
        echo 'Error in token' . $menu_id;
      } else {
        $date1 = $_GET['date1'];
        $date2 = $_GET['date2'];

        ///company_id
        $rCi = $mysqli->query("SELECT company_id FROM maker_users WHERE id='$user_id' LIMIT 1 ") or die($mysqli->error);
        $rowCi = $rCi->fetch_array(MYSQLI_ASSOC);
        $company_id = $rowCi['company_id'];

        $ListForm = array();
        //echo "SELECT * FROM maker_form_received WHERE company_id='$company_id' ORDER BY `date` ASC <br>";
        $result = $mysqli->query("SELECT * FROM maker_forms_received WHERE company_id='$company_id' AND `date`>='$date1' AND `date`<='$date2' ORDER BY `date` DESC ");
        $f = 0;

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          $f++;

          $ListForm[] = $row;
        }





        ///
        header("HTTP/1.1 200 OK");
        //echo $fecha.'*'.$empresa_id;
        if ($f == 0) {
          echo '[{"error":"There are no forms for these dates"}]';
        } else {
          echo json_encode($ListForm);
        }
      }
    } else
      /// fin de menu
      if ($ref == 'category-list') {

        $user_id = $_GET['user_id'];
        $time = $_GET['time'];
        $token = $_GET['token'];

        $tokenB = md5($user_id . $time . $keyEncrypter);

        if ($token != $tokenB) {
          header("HTTP/1.1 403 ERROR");
          echo 'Error in token';
        } else {
          ///company_id
          $rCi = $mysqli->query("SELECT company_id FROM maker_users WHERE id='$user_id' LIMIT 1 ") or die($mysqli->error);
          $rowCi = $rCi->fetch_array(MYSQLI_ASSOC);
          $company_id = $rowCi['company_id'];

          $categories = array();

          $result = $mysqli->query("SELECT * FROM maker_categories WHERE company_id='$company_id' ORDER BY active DESC, position ASC, category ASC ");
          $c = 0;
          while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $c++;
            if ($row['active'] == 0) {
              $row['active'] = false;
            } else {
              $row['active'] = true;
            }


            $categories[] = $row;
          }


          ///
          header("HTTP/1.1 200 OK");
          //echo $fecha.'*'.$empresa_id;
          if ($c > 0) {
            echo json_encode($categories);
          } else {
            echo '[{"error":"Categories empty"}]';
          }
        }
      } else
        /// fin list categories
        if ($ref == 'product-list') {

          $user_id = $_GET['user_id'];
          $time = $_GET['time'];
          $token = $_GET['token'];

          $tokenB = md5($user_id . $time . $keyEncrypter);

          if ($token != $tokenB) {
            header("HTTP/1.1 403 ERROR");
            echo 'Error in token';
          } else {
            ///company_id
            $rCi = $mysqli->query("SELECT company_id FROM maker_users WHERE id='$user_id' LIMIT 1 ") or die($mysqli->error);
            $rowCi = $rCi->fetch_array(MYSQLI_ASSOC);
            $company_id = $rowCi['company_id'];

            $category_id = $_GET['category_id'];
            $products = array();

            $result = $mysqli->query("SELECT * FROM maker_products WHERE category_id='$category_id' AND company_id='$company_id' ORDER BY active DESC, position ASC , product ASC");
            $c = 0;
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
              $c++;
              if ($row['active'] == 0) {
                $row['active'] = false;
              } else {
                $row['active'] = true;
              }

              if ($row['home'] == 0) {
                $row['home'] = false;
              } else {
                $row['home'] = true;
              }
              $products[] = $row;
            }


            ///
            header("HTTP/1.1 200 OK");
            //echo $fecha.'*'.$empresa_id;
            if ($c > 0) {
              echo json_encode($products);
            } else {
              echo '[]';
            }
          }
        } else
          //// WEB
          if ($ref == 'menu-web-head' || $ref == 'menu-web-footer') {

            $company_id = $_GET['company_id'];
            $tokenWeb = $_GET['tokenWeb'];

            $tokenB = md5($company_id . $keyEncrypter);

            if ($tokenWeb != $tokenB) {
              header("HTTP/1.1 403 ERROR");
              echo 'Error in token' . $company_id . ':' . $tokenWeb . '*' . $tokenB;
            } else {

              $filter = "";
              if ($ref == 'menu-web-head') {
                $filter = "AND head=1";
              } else if ($ref == 'menu-web-footer') {
                $filter = "AND foot=1";
              }

              $menu = array();

              $result = $mysqli->query("SELECT id, menu, link, submenu, `type` FROM maker_menu WHERE company_id='$company_id' AND menu_id='0' $filter ORDER BY position ASC ") or die($mysqli->error);
              $limit = mysqli_num_rows($result) + 1;
              $a = 0;
              while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $a++;

                if ($row['type'] == 'Products') { //cargamos las categorias
                  //position products
                  //$categorias=array();
                  /// load catefgories
                  //$rprod = $mysqli->query("SELECT id, category AS menu FROM maker_categories WHERE company_id='$company_id' ORDER BY position ASC ");
                  // load products
                  $rprod = $mysqli->query("SELECT maker_categories.id, maker_categories.category AS menu FROM maker_categories WHERE maker_categories.company_id='$company_id' AND active='1' ORDER BY maker_categories.position ASC ");

                  while ($rowP = $rprod->fetch_array(MYSQLI_ASSOC)) {
                    //$rowP['link']='/maker_products/'.clean_link(trim($rowP['menu']));///categories
                    $rowP['link'] = '/categoria/' . clean_link(trim($rowP['menu'])); ///product
                    $rowP['submenu'] = false;
                    $rowP['submenus'] = array();

                    // $categorias[]=$rowP;
                    $menu[] = $rowP;
                  }
                }

                if ($row['submenu'] == 0) {
                  $row['submenu'] = false;
                } else {
                  $row['submenu'] = true;
                }
                //
                if ($row['type'] != 'External Link') {
                  if ($row['type'] == 'Home') {
                    $row['link'] = '/';
                  } else if ($row['type'] == 'Info') {
                    $row['link'] = '/pagina/' . clean_link(trim($row['menu']));
                  } else if ($row['type'] == 'Categories') {
                    $row['link'] = '/pagina/Lineas-de-Producto';
                  } else if ($row['type'] == 'Sub Categories') {
                    $row['submenu'] = true;
                    $row['link'] = '#';
                    $row['submenus'] = array();
                    ///
                    $rct = $mysqli->query("SELECT maker_categories.id, maker_categories.category AS menu FROM maker_categories WHERE maker_categories.company_id='$company_id' AND active='1' ORDER BY maker_categories.position ASC ");

                    while ($rowct = $rct->fetch_array(MYSQLI_ASSOC)) {
                      $rowct['link'] = '/categoria/' . clean_link(trim($rowct['menu'])); ///product
                      $rowct['submenu'] = false;
                      $rowct['submenus'] = array();

                      // $categorias[]=$rowct;
                      $row['submenus'][] = $rowct;
                    }
                    ///

                  } else if ($row['type'] == 'Products') {
                    $row['link'] = '/products'; /// load categories
                  } else if ($row['type'] == 'Gallery') {
                    $row['link'] = '/pagina/' . clean_link(trim($row['menu']));
                  } else if ($row['type'] == 'Form') {
                    $row['link'] = '/pagina/' . clean_link(trim($row['menu']));
                  } else if ($row['type'] == 'News') {
                    $row['link'] = '/news/';
                  } else if ($row['type'] == 'Events') {
                    $row['link'] = '/events/';
                  }
                }

                $m_id = $row['id'];
                /// cargamos el submenu
                if ($row['type'] !== 'Categories') {
                  $submenu = array();
                  $resultSM = $mysqli->query("SELECT id, menu, link, submenu FROM maker_menu WHERE menu_id='$m_id' ORDER BY position ASC ");
                  $sb = 0;
                  while ($rowSM = $resultSM->fetch_array(MYSQLI_ASSOC)) {
                    $sb++;

                    $submenu[] = $rowSM;
                  }


                  $row['submenus'] = $submenu;
                }



                if ($row['type'] != 'Products') {
                  $menu[] = $row;
                }
              }

              //
              $rLG = $mysqli->query("SELECT image1 FROM maker_companies WHERE id='$company_id' LIMIT 1") or die($mysqli->error);
              $rowLG = $rLG->fetch_array(MYSQLI_ASSOC);

              $response = array();
              $response[] = $menu;
              $response[] = $rowLG['image1'];


              ///
              header("HTTP/1.1 200 OK");
              //echo $fecha.'*'.$empresa_id;
              //echo json_encode($menu);
              echo json_encode($response);
            }
          } else
      if ($ref == 'menu-web-categories') {

            $company_id = $_GET['company_id'];
            $tokenWeb = $_GET['tokenWeb'];

            $tokenB = md5($company_id . $keyEncrypter);

            if ($tokenWeb != $tokenB) {
              header("HTTP/1.1 403 ERROR");
              echo 'Error in token' . $company_id . ':' . $tokenWeb . '*' . $tokenB;
            } else {

              $categorias = array();
              /// si hay, se carga las categorias de productos
              $rprod = $mysqli->query("SELECT id, category AS menu FROM maker_categories WHERE company_id='$company_id' ORDER BY position ASC ");

              while ($rowP = $rprod->fetch_array(MYSQLI_ASSOC)) {
                $rowP['link'] = '/maker_products/' . clean_link(trim($rowP['menu']));
                $rowP['submenu'] = false;
                $rowP['submenus'] = array();

                $categorias[] = $rowP;
              }


              ///
              header("HTTP/1.1 200 OK");
              //echo $fecha.'*'.$empresa_id;
              echo json_encode($categorias);
            }
          } else
      
      if ($ref == 'page-web') {

            $company_id = $_GET['company_id'];
            $tokenWeb = $_GET['tokenWeb'];


            $tokenB = md5($company_id . $keyEncrypter);

            if ($tokenWeb != $tokenB) {
              header("HTTP/1.1 403 ERROR");
              echo 'Error in token' . $company_id . ':' . $tokenWeb . '*' . $tokenB;
            } else {
              $type = $_GET['type'];
              $filter = "";
              $company_id = $_GET['company_id'];

              if ($type != '') {
                $filter = "AND maker_menu.type='$type'";
              }

              $page = array();
              $content = array();
              //echo "SELECT * FROM maker_menu WHERE company_id='$company_id' $filter ORDER BY position ASC <br>";
              /// menu
              $result = $mysqli->query("SELECT * FROM maker_menu WHERE company_id='$company_id' $filter ORDER BY position ASC");
              $menu_id = 0;
              while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                if ($type != 'Home') {
                  //echo clean_link($row['menu']).' == '.$_GET['name'].'<br>';
                  if (clean_link($row['menu']) == $_GET['name']) {
                    $page[] = $row;
                    $menu_id = $row['id'];
                    break;
                  }
                } else {
                  $page[] = $row;
                  $menu_id = $row['id'];
                }
              }

              //echo $company_id.'*'.$page;
              /// content

              $resultC = $mysqli->query("SELECT maker_content_blocks.* FROM maker_content_blocks  WHERE menu_id='$menu_id' AND company_id='$company_id' ORDER BY maker_content_blocks.position ASC LIMIT 1");
              $rowC = $resultC->fetch_array(MYSQLI_ASSOC);
              $content = $rowC;
              $page[] = $content;
              //
              // form
              if ($type == 'Form') {
                $form = array();
                //echo "SELECT * FROM maker_form WHERE company_id='$company_id' AND menu_id='$menu_id' ORDER BY position ASC <br>";
                $resultF = $mysqli->query("SELECT * FROM maker_form WHERE company_id='$company_id' AND menu_id='$menu_id' ORDER BY position ASC");
                while ($rowF = $resultF->fetch_array(MYSQLI_ASSOC)) {
                  $rowF['response'] = '';
                  $form[] = $rowF;
                }
                $page[] = $form;
              } else if ($type == 'Home') {
                $products = array();

                $result = $mysqli->query("SELECT * FROM maker_products WHERE company_id='$company_id' AND home='1' AND active='1' ORDER BY category_id, product") or die($mysqli->error);
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                  $row['folder'] = 'maker_products';
                  $row['linkURL'] = 'producto/' . clean_link($row['titulo']);
                  $products[] = $row;
                }
                $page[] = $products;
              }


              ///
              header("HTTP/1.1 200 OK");
              //echo $fecha.'*'.$empresa_id;
              echo json_encode($page);
            }
          } else if ($ref == 'categories-web') {

            $company_id = $_GET['company_id'];
            $tokenWeb = $_GET['tokenWeb'];
            $type = $_GET['type'];

            $tokenB = md5($company_id . $keyEncrypter);

            if ($tokenWeb != $tokenB) {
              header("HTTP/1.1 403 ERROR");
              echo 'Error in token' . $company_id . ':' . $tokenWeb . '*' . $tokenB;
            } else {
              $category = $_GET['category'];
              if ($category == '') {
                /// load categories
                $categories = array();
                $result = $mysqli->query("SELECT * FROM maker_categories WHERE company_id='$company_id' AND active='1' ORDER BY position ASC");
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                  $categories[] = $row;
                }
                $response = array();
                $response[] = $categories;

                header("HTTP/1.1 200 OK");
                //echo $fecha.'*'.$empresa_id;
                echo json_encode($response);
              } else if ($category != '') {
                //category id
                $categories = array();
                $resultC = $mysqli->query("SELECT * FROM maker_categories WHERE company_id='$company_id' AND active='1' ORDER BY position ASC");
                $category_id = 0;
                while ($rowC = $resultC->fetch_array(MYSQLI_ASSOC)) {
                  if (clean_link($rowC['category']) == $_GET['category']) {
                    $category_id = $rowC['id'];
                    $categories[] = $rowC;
                    break;
                  }
                }
                //echo '***'.$category_id.'<br>';
                ///load products
                $products = array();
                $result = $mysqli->query("SELECT * FROM maker_products WHERE category_id='$category_id' ORDER BY position ASC");
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                  $products[] = $row;
                }
                $response = array();
                $response[] = $categories;
                $response[] = $products;
                header("HTTP/1.1 200 OK");
                //echo $fecha.'*'.$empresa_id;
                echo json_encode($response);
              }
            }
          } else if ($ref == 'product-web') {

            $company_id = $_GET['company_id'];
            $tokenWeb = $_GET['tokenWeb'];
            $type = $_GET['type'];

            $tokenB = md5($company_id . $keyEncrypter);

            if ($tokenWeb != $tokenB) {
              header("HTTP/1.1 403 ERROR");
              echo 'Error in token';
            } else {
              $category_id = $_GET['category_id'];
              $product = $_GET['product'];

              /// load categories
              $prod = array();
              //echo "SELECT * FROM maker_products WHERE category_id='$category_id' AND active='1' ORDER BY position ASC <br>";
              $result = $mysqli->query("SELECT * FROM maker_products WHERE category_id='$category_id' AND active='1' ORDER BY position ASC");
              while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                if (clean_link($row['product']) == $product) {
                  $prod = $row;
                  break;
                }
              }
              header("HTTP/1.1 200 OK");
              //echo $fecha.'*'.$empresa_id;
              echo json_encode($prod);
            }
          } else if ($ref == 'search-web') {

            $company_id = $_GET['company_id'];
            $tokenWeb = $_GET['tokenWeb'];
            $type = $_GET['type'];

            $tokenB = md5($company_id . $keyEncrypter);

            if ($tokenWeb != $tokenB) {
              header("HTTP/1.1 403 ERROR");
              echo 'Error in token';
            } else {
              $search = $_GET['search'];

              $list_prod = array();
              //echo "SELECT * FROM maker_products WHERE category_id='$category_id' AND active='1' ORDER BY position ASC <br>";
              $filtre = "(MATCH( maker_categories.category, maker_products.product,products.description, maker_products.size) AGAINST ('$search*' IN BOOLEAN MODE))";

              $result = $mysqli->query("SELECT maker_products.* FROM maker_categories,products WHERE $filtre AND maker_products.active='1' GROUP BY maker_products.id ORDER BY maker_products.product ASC");
              while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $list_prod[] = $row;
              }
              //



              header("HTTP/1.1 200 OK");
              //echo $fecha.'*'.$empresa_id;
              echo json_encode($list_prod);
            }
          }
  /// The End WEB

  /**/
}
/// FIN metodo GET  ******************




else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $ref = $_GET['ref'];
  //echo '--'.$_REQUEST['params'];
  //
  if ($ref == 'test') {

    header("HTTP/1.1 200 OK");
    echo '[{"id":"' . $data['email'] . '","documento":"' . $data['password'] . '-' . $_SERVER['REQUEST_METHOD'] . '"}]';
  } else
    if ($ref == 'Login') {
    $email = $data['email'];
    $password = $data['password'];
    $password2 = md5($password . 'Yhj8');
    $time = time() + (4 * 60 * 60); //4H 

    $myArray = array();
    $result = $mysqli->query("SELECT * FROM maker_users WHERE email='$email' AND password='$password2' AND active='1' LIMIT 1");

    $token = '';
    $rowR = [];
    $us = 0;
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
      $us++;
      $token = md5($row['id'] . $time . $keyEncrypter);
      $rowR['id'] = $row['id'];
      $rowR['name'] = $row['name'];
      $rowR['email'] = $row['email'];
      $rowR['type'] = $row['type'];
      $rowR['user_time_life'] = $time;
      $rowR['token'] = $token;
      $rowR['Maker_id'] = $row['id'];

      $myArray[] = $rowR;
    }



    if ($us == 0) {
      header("HTTP/1.1 403 Error");
      echo '[{"error":"Error in the email or password"}]';
    } else {


      header("HTTP/1.1 200 OK");

      //echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      //echo json_encode($myArray);
      $result = json_encode($myArray);
      if ($result != '[]') {
        //$result=trim($result,'}]');
        //echo $result.',"token": "'.$token.'","user_time_life": "'.$time.'"}]';
        echo $result;
      } else {
        //echo $result; 
        echo '[{"error":"Error in the email or password"}]';
      }
    }
  } else
        if ($ref == 'Change Password') {
    $email = $data['email'];
    $password = $data['password'];
    $password2 = md5($password . 'Yhj8');
    $time = time() + (4 * 60 * 60); //4H 

    $myArray = array();
    $result = $mysqli->query("SELECT users.id, users.name, hoteles.hotel FROM maker_users, hoteles WHERE users.email='$email' AND users.active='1' AND hoteles.id=users.company_id LIMIT 1");
    $user_id = 0;
    $user_name = '';
    $company = '';
    $us = 0;
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
      $us++;

      $user_id = $row['id'];
      $company = $row['company'];
      $user_name = $row['name'];
    }



    if ($us == 0) {
      header("HTTP/1.1 403 Error");
      echo '[{"error":"This email does not register in our system: ' . $email . '"}]';
    } else {

      //send mail
      $message = '<strong>' . $user_name . '</strong><br><br>To change the password in our system, please click on the link: <br><br><strong>Link: </strong> <a href="https://diegosierra.cityciudad.com/api/change-pass.php?id=' . $user_id . '&email=' . $email . '&pass=' . $password2 . '">https://diegosierra.cityciudad.com/api/change-pass.php?id=' . $user_id . '&email=' . $email . '&pass=' . $password2 . '</a><br><br><strong>Email:</strong> ' . $email . '<br><strong>New Password:</strong> ' . $password . '<br><br>';
      ob_start();
      include("mail.php");
      $html = ob_get_contents();
      ob_end_clean();

      $subjet = 'Change Password';


      $from_email = 'soporte@cityciudad.com';
      // echo $html;
      $send_email = $email;

      if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
        $eol = "\r\n";
      } elseif (strtoupper(substr(PHP_OS, 0, 3) == 'MAC')) {
        $eol = "\r";
      } else {
        $eol = "\n";
      }
      $header = "Content-type: text/html" . $eol;
      //dirección del remitente
      $header .= 'From: ' . $company . ' <' . $from_email . '>' . $eol;
      $header .= 'Reply-To: ' . $company . ' <' . $from_email . '>' . $eol;
      $header .= "Message-ID:<" . time() . " TheSystem@" . $_SERVER['SERVER_NAME'] . ">" . $eol;
      $header .= "X-Mailer: PHP v" . phpversion() . $eol;
      $header .= 'MIME-Version: 1.0' . $eol;
      //////
      mail($send_email, $subjet, $html, $header);
      /// the end send mail

      header("HTTP/1.1 200 OK");
      echo '[{"ok":"Check your email ' . $email . ' to finish the process."}]';
    }
  }
  ///Fin Ingreso
  else if ($ref == 'save-menu') {

    $user_id = $data['user_id'];
    $time_life = $data['time_life'];
    $token = $data['token'];
    //$documento=clean_numbers($data['documento']);
    /// primero validamos el token
    $tokenBase = md5($user_id . $time_life . $keyEncrypter);

    if ($tokenBase != $token) {
      header("HTTP/1.1 400 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"Acceso incorrecto"}]';
    } else {
      ///company_id
      $error = '';

      $rCi = $mysqli->query("SELECT company_id FROM maker_users WHERE id='$user_id' LIMIT 1 ");
      $error = $mysqli->error;
      $rowCi = $rCi->fetch_array(MYSQLI_ASSOC);
      $company_id = $rowCi['company_id'];
      /// run
      $cod = time();
      $a = 0;
      $datos = '';

      foreach ($data['menu'] as $menu) {
        $a++;
        $m_id = $menu['id'];
        $m_menu_id = $menu['menu_id'];
        $m_menu = $menu['menu'];
        $m_type = $menu['type'];
        $m_link = $menu['link'];
        $m_head = $menu['head'];
        $m_foot = $menu['foot'];
        $m_side = $menu['side'];
        $m_head === true ? $m_head = 1 : $m_head = 0;
        $m_foot === true ? $m_foot = 1 : $m_foot = 0;
        $m_side === true ? $m_side = 1 : $m_side = 0;
        $m_position = $menu['position'];
        $m_submenu = $menu['submenu'];
        $m_submenu === true ? $m_submenu = 1 : $m_submenu = 0;
        $m_metadescription = $menu['metadescription'];
        $m_metakeywords = $menu['metakeywords'];
        //
        if ($m_id > 1000000) {
          ///Nuevo Registro
          $mysqli->query("INSERT INTO maker_menu (company_id,menu_id,menu,`type`,link,head,foot,side,position,submenu,metadescription,metakeywords,cod) VALUES ('$company_id','$m_menu_id','$m_menu','$m_type','$m_link','$m_head','$m_foot','$m_side','$m_position','$m_submenu','$m_metadescription','$m_metakeywords','$cod')");
          $error = $mysqli->error;
        } else {
          ///actualizar
          $mysqli->query("UPDATE maker_menu SET company_id='$company_id', menu_id='$m_menu_id',menu='$m_menu',`type`='$m_type',link='$m_link',head='$m_head',foot='$m_foot',side='$m_side',position='$m_position',submenu='$m_submenu',metadescription='$m_metadescription',metakeywords='$m_metakeywords',cod='$cod' WHERE id='$m_id'");
          $error = $mysqli->error;
        }
      }

      /* header("HTTP/1.1 401 OK");
      echo '[{"error":"' . $error . '"}]';
      return; */
      /// borramos los que no están
      $mysqli->query("DELETE FROM maker_menu WHERE company_id='$company_id' AND cod!='$cod'");
      $error = $mysqli->error;
      //
      $menu = array();
      $result = $mysqli->query("SELECT * FROM maker_menu WHERE menu_id='0' AND company_id='$company_id' ORDER BY position ASC ");
      $error = $mysqli->error;
      $limit = mysqli_num_rows($result) + 1;
      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

        if ($row['head'] == 0) {
          $row['head'] = false;
        } else {
          $row['head'] = true;
        }
        if ($row['foot'] == 0) {
          $row['foot'] = false;
        } else {
          $row['foot'] = true;
        }
        if ($row['side'] == 0) {
          $row['side'] = false;
        } else {
          $row['side'] = true;
        }
        if ($row['submenu'] == 0) {
          $row['submenu'] = false;
        } else {
          $row['submenu'] = true;
        }

        /// cargamos el submenu
        $m_id = $row['id'];
        $submenu = array();
        $resultSM = $mysqli->query("SELECT * FROM maker_menu WHERE menu_id='$m_id' ORDER BY position ASC ");
        $error = $mysqli->error;
        while ($rowSM = $resultSM->fetch_array(MYSQLI_ASSOC)) {
          if ($rowSM['head'] == 0) {
            $rowSM['head'] = false;
          } else {
            $rowSM['head'] = true;
          }
          if ($rowSM['foot'] == 0) {
            $rowSM['foot'] = false;
          } else {
            $rowSM['foot'] = true;
          }
          if ($rowSM['side'] == 0) {
            $rowSM['side'] = false;
          } else {
            $rowSM['side'] = true;
          }
          if ($rowSM['submenu'] == 0) {
            $rowSM['submenu'] = false;
          } else {
            $rowSM['submenu'] = true;
          }
          $submenu[] = $rowSM;
        }

        $row['submenus'] = $submenu;
        $menu[] = $row;
      }
      /* header("HTTP/1.1 400 OK");
     echo '[{"error":"'.$error.'"}]';  */
      ///
      header("HTTP/1.1 200 OK");
      //echo $fecha.'*'.$empresa_id;
      echo json_encode($menu);

      //echo '[{"ok":"yess"}]';
    }
  } else if ($ref == 'update-campo') {

    $user_id = $data['user_id'];
    $time_life = $data['time_life'];
    $token = $data['token'];
    //$documento=clean_numbers($data['documento']);
    /// primero validamos el token
    $tokenBase = md5($user_id . $time_life . $keyEncrypter);

    if ($tokenBase != $token) {
      header("HTTP/1.1 202 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"yes"}]';
    } else {
      $campo = $data['campo'];
      $id = $data['id'];
      $folder = $data['folder'];
      $val = $data['val'];
      ///
      $action = "UPDATE $folder SET $campo='$val' WHERE id='$id'";
      $rUp = $mysqli->query($action);
      $err = $mysqli->error;
      ///
      header("HTTP/1.1 200 OK");
      echo '[{"update":"ok"}]';
      //echo '[{"update":"'.$action.'"}]';
    }
  } else if ($ref == 'save-form') {

    $user_id = $data['user_id'];
    $time_life = $data['time_life'];
    $token = $data['token'];

    $tokenBase = md5($user_id . $time_life . $keyEncrypter);

    if ($tokenBase != $token) {
      header("HTTP/1.1 403 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"error in token"}]';
    } else {
      ///company_id
      $rCi = $mysqli->query("SELECT company_id FROM maker_users WHERE id='$user_id' LIMIT 1 ") or die($mysqli->error);
      $rowCi = $rCi->fetch_array(MYSQLI_ASSOC);
      $company_id = $rowCi['company_id'];
      /// run
      $cod = time();
      $a = 0;
      $datos = '';
      foreach ($data['listForm'] as $form) {
        $a++;
        $m_id = $form['id'];
        $m_menu_id = $form['menu_id'];
        $m_name = $form['name'];
        $m_type = $form['type'];
        $m_required = $form['required'];
        $m_position = $form['position'];
        //
        if ($m_id > 1000000) {
          ///Nuevo Registro
          $mysqli->query("INSERT INTO maker_form (company_id,menu_id,`name`,`type`,`required`,position,cod) VALUES ('$company_id','$m_menu_id','$m_name','$m_type','$m_required','$m_position','$cod')") or die($mysqli->error);
        } else {
          ///actualizar
          $mysqli->query("UPDATE maker_form SET company_id='$company_id', menu_id='$m_menu_id',`name`='$m_name',`type`='$m_type',`required`='$m_required',position='$m_position',cod='$cod' WHERE id='$m_id'") or die($mysqli->error);
        }
      }
      /// borramos los que no están
      $mysqli->query("DELETE FROM maker_form WHERE company_id='$company_id' AND menu_id='$m_menu_id' AND cod!='$cod'") or die($mysqli->error);
      //
      $formL = array();
      $result = $mysqli->query("SELECT * FROM maker_form WHERE menu_id='$m_menu_id' AND company_id='$company_id' ORDER BY position ASC ");

      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {


        if ($row['required'] == 0) {
          $row['required'] = false;
        } else {
          $row['required'] = true;
        }

        $formL[] = $row;
      }

      ///
      header("HTTP/1.1 200 OK");
      //echo $fecha.'*'.$empresa_id;
      echo json_encode($formL);

      //echo '[{"ok":"yess"}]';
    }
  } else if ($ref == 'save-gallery') {

    $user_id = $data['user_id'];
    $time_life = $data['time_life'];
    $token = $data['token'];

    $tokenBase = md5($user_id . $time_life . $keyEncrypter);

    if ($tokenBase != $token) {
      header("HTTP/1.1 403 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"error in token"}]';
    } else {
      ///company_id
      $rCi = $mysqli->query("SELECT company_id FROM maker_users WHERE id='$user_id' LIMIT 1 ") or die($mysqli->error);
      $rowCi = $rCi->fetch_array(MYSQLI_ASSOC);
      $company_id = $rowCi['company_id'];
      /// run
      $cod = time();
      $a = 0;
      $datos = '';
      foreach ($data['listGalleries'] as $gallery) {
        $a++;
        $m_id = $gallery['id'];
        $m_content_id = $gallery['content_id'];
        $m_image = $gallery['image'];
        $m_title = $gallery['title'];
        $m_description = $gallery['description'];
        $m_position = $gallery['position'];

        //
        if ($m_id > 1000000) {
          ///Nuevo Registro
          $mysqli->query("INSERT INTO maker_gallery (content_id,`image`,`title`,`description`,position,cod) VALUES ('$m_content_id','$m_image','$m_title','$m_description','$m_position','$cod')") or die($mysqli->error);
        } else {
          ///actualizar
          $mysqli->query("UPDATE maker_gallery SET content_id='$m_content_id', `image`='$m_image',`title`='$m_title',`description`='$m_description',position='$m_position',cod='$cod' WHERE id='$m_id'") or die($mysqli->error);
        }
      }
      /// borramos los que no están
      $mysqli->query("DELETE FROM maker_gallery WHERE content_id='$m_content_id' AND cod!='$cod'") or die($mysqli->error);
      //
      $response = array();
      $result = $mysqli->query("SELECT * FROM maker_gallery WHERE content_id='$m_content_id' ORDER BY position ASC ");

      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

        $response[] = $row;
      }

      ///
      header("HTTP/1.1 200 OK");
      //echo $fecha.'*'.$empresa_id;
      echo json_encode($response);

      //echo '[{"ok":"yess"}]';
    }
  } else if ($ref == 'save-form-answer') {

    $user_id = $data['user_id'];
    $time_life = $data['time_life'];
    $token = $data['token'];

    $tokenBase = md5($user_id . $time_life . $keyEncrypter);

    if ($tokenBase != $token) {
      header("HTTP/1.1 403 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"error in token"}]';
    } else {

      foreach ($data['listForm'] as $form) {
        $a++;
        $company_id = $form['company_id'];
        $m_id = $form['id'];
        $m_response = $form['response'];
        $m_state = $form['state'];
        $now = date('Y-m-d H:i');
        if ($m_response == '') {
          $now = '';
        }
        //
        ///actualizar
        $mysqli->query("UPDATE maker_forms_received SET response='$m_response',date_response='$now', `state`='$m_state' WHERE id='$m_id'") or die($mysqli->error);
      }
      //
      $date1 = $_GET['date1'];
      $date2 = $_GET['date2'];
      $formL = array();
      $result = $mysqli->query("SELECT * FROM maker_forms_received WHERE company_id='$company_id' AND `date`>='$date1' AND `date`<='$date2' ORDER BY `date` DESC ");

      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $formL[] = $row;
      }

      ///
      header("HTTP/1.1 200 OK");
      //echo $fecha.'*'.$empresa_id;
      echo json_encode($formL);

      //echo '[{"ok":"yess"}]';
    }
  } else if ($ref == 'save-content') {

    $user_id = $data['user_id'];
    $time_life = $data['time_life'];
    $token = $data['token'];
    $content = $data['content'];
    $cont_id = $data['cont_id'];
    $tokenBase = md5($user_id . $time_life . $keyEncrypter);

    if ($tokenBase != $token) {
      header("HTTP/1.1 403 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"error in token"}]';
    } else {
      /// se procesa
      $cod = time();

      $c_id = $content['id'];
      $c_menu_id = $cont_id;
      $c_title = $content['title'];
      $c_subtitle = $content['subtitle'];
      $c_text1 = $content['text1'];
      $c_text2 = $content['text2'];
      $c_text3 = $content['text3'];
      $c_text4 = $content['text4'];
      $c_image1 = $content['image1'];
      $c_image2 = $content['image2'];
      $c_image3 = $content['image3'];
      $c_image4 = $content['image4'];
      $c_video = $content['video'];
      $c_position = $content['position'];
      $c_link = $content['link'];
      //echo $cont_id.'**'.$c_menu_id.'+';
      $nuevo = 'no';
      $resultB = $mysqli->query("SELECT id FROM maker_content_blocks WHERE menu_id='$cont_id' LIMIT 1");
      if (mysqli_num_rows($resultB) == 0) {
        $nuevo = 'si';
      }

      if ($c_id == 0 or $nuevo == 'si') {
        ///Nuevo Registro
        $action = "INSERT INTO maker_content_blocks (menu_id,	title,	subtitle,	text1,	text2,	text3,	text4,	image1,	image2,	image3,	image4,	video,	position,	link) VALUES ('$c_menu_id',	'$c_title',	'$c_subtitle',	'$c_text1',	'$c_text2',	'$c_text3',	'$c_text4',	'$c_image1',	'$c_image2',	'$c_image3',	'$c_image4',	'$c_video',	'$c_position',	'$c_link')";
        $mysqli->query($action) or die($mysqli->error);
        ///

      } else if ($c_id != 1000000) {
        ///actualizar
        $action = "UPDATE maker_content_blocks SET title='$c_title',	subtitle='$c_subtitle',	text1='$c_text1',	text2='$c_text2',	text3='$c_text3',	text4='$c_text4',	image1='$c_image1',	image2='$c_image2',	image3='$c_image3',	image4='$c_image4',	video='$c_video',	position='$c_position',	link='$c_link' WHERE id='$c_id'";
        $mysqli->query($action) or die($mysqli->error);
      }

      //echo '+'.$action.'*';

      /// borramos los que no están
      //$mysqli->query("DELETE FROM maker_menu WHERE cod!='$cod'") or die($mysqli->error); 
      //

      $result = $mysqli->query("SELECT * FROM maker_content_blocks WHERE menu_id='$cont_id' LIMIT 1");
      $new_cont = array();
      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

        $new_cont[] = $row;
      }

      ///
      header("HTTP/1.1 200 OK");
      //echo $fecha.'*'.$empresa_id;
      echo json_encode($new_cont);

      //echo '[{"ok":"yess"}]';
    }
  }
  /// 
  else if ($ref == 'save-prod') {

    $user_id = $data['user_id'];
    $time_life = $data['time_life'];
    $token = $data['token'];

    $tokenBase = md5($user_id . $time_life . $keyEncrypter);

    if ($tokenBase != $token) {
      header("HTTP/1.1 403 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"error in token"}]';
    } else {
      ///company_id
      $rCi = $mysqli->query("SELECT company_id FROM maker_users WHERE id='$user_id' LIMIT 1 ") or die($mysqli->error);
      $rowCi = $rCi->fetch_array(MYSQLI_ASSOC);
      $company_id = $rowCi['company_id'];

      /// se procesa
      $cod = time();
      $product = $data['product'];
      //
      $insertA = "";
      $insertB = "";
      $UPDATE = "";
      //
      foreach ($request as $campo => $valor) {
        // $update="$campo='$valor',";
        //$valor=addslashes($valor);
        if ($campo != 'id') {
          $insertA .= $campo . ',';
          $insertB .= "'$valor',";
          $UPDATE .= "`$campo`='$valor',";
        } else {
          $id = $valor;
        }
      }

      $product_id = $product['id'];
      $c_id = $product['id'];
      $c_category_id = $product['category_id'];
      $c_product = $product['product'];
      $c_ref = $product['ref'];
      $c_description = $product['description'];
      $c_description2 = $product['description2'];
      $c_price = $product['price'];
      $c_size = $product['size'];
      $c_color = $product['color'];
      $c_image1 = $product['image1'];
      $c_image2 = $product['image2'];
      $c_image3 = $product['image3'];
      $c_image4 = $product['image4'];
      $c_position = $product['position'];
      $c_options = $product['options'];
      $c_active = $product['active'];
      $c_home = $product['home'];
      $now = time();

      if ($c_id == 0 or $c_id > 1000000) {
        ///Nuevo Registro
        $action = "INSERT INTO maker_products (category_id,product,ref,description,description2,price,size,color,image1,image2,image3,image4,position,options,home,active,cod) VALUES ('$c_category_id','$c_product','$c_ref','$c_description','$c_description2','$c_price','$c_size','$c_color','$c_image1','$c_image2','$c_image3','$c_image4','$c_position','$c_options','$c_home','$c_active','$now')";
        $mysqli->query($action) or die($mysqli->error);
        ///

      } else {
        ///actualizar
        $action = "UPDATE maker_products SET category_id='$c_category_id',product='$c_product',ref='$c_ref',description='$c_description',description2='$c_description2',price='$c_price',size='$c_size',color='$c_color',image1='$c_image1',image2='$c_image2',image3='$c_image3',image4='$c_image4',position='$c_position',options:'$c_options',home='$c_home',active='$c_active', cod='$now' WHERE id='$c_id'";
        $mysqli->query($action) or die($mysqli->error);
      }
      //header("HTTP/1.1 200 OK");
      //echo '+'.$action.'*';

      /// borramos los que no están
      //$mysqli->query("DELETE FROM maker_menu WHERE cod!='$cod'") or die($mysqli->error); 
      //
      /*
      $result = $mysqli->query("SELECT * FROM maker_products WHERE cod='$now' LIMIT 1");
      $new_prod = array();
      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
$new_prod[] = $row;
      }
*/
      ///
      header("HTTP/1.1 200 OK");
      //echo $fecha.'*'.$empresa_id;
      //echo json_encode($new_prod);
      echo '[{"save":"product ok"}]';
    }
  }
  /// 
  else if ($ref == 'save-category') {

    $user_id = $data['user_id'];
    $time_life = $data['time_life'];
    $token = $data['token'];

    $tokenBase = md5($user_id . $time_life . $keyEncrypter);

    if ($tokenBase != $token) {
      header("HTTP/1.1 403 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"Error in Token"}]';
    } else {
      ///company_id
      $rCi = $mysqli->query("SELECT company_id FROM maker_users WHERE id='$user_id' LIMIT 1 ") or die($mysqli->error);
      $rowCi = $rCi->fetch_array(MYSQLI_ASSOC);
      $company_id = $rowCi['company_id'];
      /// se procesa
      $cod = time();
      $a = 0;
      $datos = '';
      foreach ($data['categories'] as $category) {
        $a++;
        $m_id = $category['id'];
        $m_category = $category['category'];
        $m_description = $category['description'];
        $m_position = $category['position'];
        $category['active']? $m_active=1 : $m_active=0;
        $m_image = $category['image'];

        if ($m_id > 1000000) {
          ///Nuevo Registro
          $mysqli->query("INSERT INTO maker_categories (company_id,category,`description`,position,`image`,active,cod) VALUES ('$company_id','$m_category','$m_description','$m_position','$m_image','$m_active','$cod')") or die($mysqli->error);
        } else {
          ///actualizar
          $mysqli->query("UPDATE maker_categories SET company_id='$company_id', category='$m_category', `description`='$m_description',position='$m_position',`image`='$m_image',active='$m_active',cod='$cod' WHERE id='$m_id'") or die($mysqli->error);
        }
      }
      /// borramos los que no están
      $mysqli->query("DELETE FROM maker_categories WHERE company_id='$company_id' AND cod!='$cod'") or die($mysqli->error);
      //
      $categories = array();

      $result = $mysqli->query("SELECT * FROM maker_categories WHERE company_id='$company_id' ORDER BY active DESC, position ASC ");
      $c = 0;
      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $c++;
        if ($row['active'] == 0) {
          $row['active'] = false;
        } else {
          $row['active'] = true;
        }


        $categories[] = $row;
      }


      ///
      header("HTTP/1.1 200 OK");
      //echo $fecha.'*'.$empresa_id;
      if ($c > 0) {
        echo json_encode($categories);
      } else {
        echo '[{"error":"Categories empty"}]';
      }

      //echo '[{"ok":"yess"}]';
    }
  }
  /// FIN Save category

  else if ($ref == 'save-product') {

    $user_id = $data['user_id'];
    $time_life = $data['time_life'];
    $token = $data['token'];

    $tokenBase = md5($user_id . $time_life . $keyEncrypter);

    if ($tokenBase != $token) {
      header("HTTP/1.1 403 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"Error in Token"}]';
    } else {
      /// se procesa
      $cod = time();
      $a = 0;
      $datos = '';
      foreach ($data['products'] as $product) {
        $a++;
        $m_id = $product['id'];
        $m_category_id = $product['category_id'];

        $m_product = $product['product'];
        $m_ref = $product['ref'];
        $m_description = $product['description'];
        $m_description2 = $product['description2'];
        $m_price = $product['price'];
        $m_size = $product['size'];
        $m_color = $product['color'];
        $m_image1 = $product['image1'];
        $m_image2 = $product['image2'];
        $m_image3 = $product['image3'];
        $m_image4 = $product['image4'];
        $m_position = $product['position'];
        $m_active = $product['active'];
        $m_home = $product['home'];
        if ($m_id > 1000000) {
          ///Nuevo Registro
          $mysqli->query("INSERT INTO maker_products (category_id,product,ref,`description`,description2,price,size,color,position,home,active,cod) VALUES ('$m_category_id','$m_product','$m_ref','$m_description','$m_description2','$m_price','$m_size','$m_color','$m_position','$m_home','$m_active','$cod')") or die($mysqli->error);
        } else {
          ///actualizar
          $mysqli->query("UPDATE maker_products SET category_id='$m_category_id',product='$m_product',ref='$m_ref',`description`='$m_description',description2='$m_description2',price='$m_price',size='$m_size',color='$m_color',position='$m_position',home='$m_home',active='$m_active',cod='$cod' WHERE id='$m_id'") or die($mysqli->error);
        }
      }
      /// borramos los que no están
      $mysqli->query("DELETE FROM maker_products WHERE category_id='$m_category_id' and cod!='$cod'") or die($mysqli->error);
      //
      $products = array();

      $result = $mysqli->query("SELECT * FROM maker_products WHERE category_id='$m_category_id' ORDER BY active DESC, position ASC , product ASC");
      $c = 0;
      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $c++;
        if ($row['active'] == 0) {
          $row['active'] = false;
        } else {
          $row['active'] = true;
        }

        if ($row['home'] == 0) {
          $row['home'] = false;
        } else {
          $row['home'] = true;
        }
        $products[] = $row;
      }


      ///
      header("HTTP/1.1 200 OK");
      //echo $fecha.'*'.$empresa_id;
      if ($c > 0) {
        echo json_encode($products);
      } else {
        echo '[{"error":"Products empty"}]';
      }

      //echo '[{"ok":"yess"}]';
    }
  }
  /// END save product
  else if ($ref == 'save') {

    $user_id = $data['user_id'];
    $time_life = $data['time_life'];
    $token = $data['token'];

    $tokenBase = md5($user_id . $time_life . $keyEncrypter);

    if ($tokenBase != $token) {
      header("HTTP/1.1 403 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"error in token"}]';
    } else {
      /// se procesa
      $folder = $data['folder'];
      $request = $data['request'];

      $insertA = "";
      $insertB = "";
      $UPDATE = "";
      $id = 0;
      foreach ($request as $campo => $valor) {
        // $update="$campo='$valor',";
        //$valor=addslashes($valor);
        if ($campo != 'id') {
          $insertA .= $campo . ',';
          $insertB .= "'$valor',";
          $UPDATE .= "`$campo`='$valor',";
        } else {
          $id = $valor;
        }
      }


      $now = time();

      if ($id == 0 or $id > 1000000) {
        ///Nuevo Registro
        $rA = trim($insertA, ',');
        $rB = trim($insertB, ',');

        $action = "INSERT INTO $folder ($rA) VALUES ($rB)";

        $mysqli->query($action);
        ///
        $resultID = $mysqli->query("SELECT id FROM $folder ORDER BY id DESC LIMIT 1");
        $rowID = $resultID->fetch_array();
        $id = $rowID['id'];
      } else {
        ///actualizar
        $u = trim($UPDATE, ',');
        $action = "UPDATE $folder SET $u WHERE id='$id'";
        $mysqli->query($action) or die($mysqli->error);
        //header("HTTP/1.1 402 ERROR");
        //echo json_decode($action);
      }

      /// borramos los que no están
      //$mysqli->query("DELETE FROM maker_menu WHERE cod!='$cod'") or die($mysqli->error); 
      //

      $result = $mysqli->query("SELECT * FROM $folder WHERE id='$id' LIMIT 1");
      $response = array();
      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $response[] = $row;
      }
      ///
      header("HTTP/1.1 200 OK");
      //echo $fecha.'*'.$empresa_id;
      echo json_encode($response);
      //echo trim($update,',');
      //echo '[{"ok":"yess"}]';
      //echo '[{"ok":"'.$action.'"}]';
    }


    //$result->close();
    //$mysqli->close();

  } else if ($ref == 'save-list') {

    $user_id = $data['user_id'];
    $time_life = $data['time_life'];
    $token = $data['token'];

    $tokenBase = md5($user_id . $time_life . $keyEncrypter);

    if ($tokenBase != $token) {
      header("HTTP/1.1 403 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"error in token"}]';
    } else {
      /// se procesa
      $folder = $_GET['folder'];
      $company_id = $data['company_id'];
      $list = $data['list'];
      $cod = time();
      //
      $columna = $_GET['campo'];
      $columna_id = $_GET['campo_id'];
      $orden = $_GET['orden'];

      //
      if (!$_GET['campo']) {
        $columna = 'company_id';
        $columna_id = $company_id;
      }

      if (!$_GET['orden']) {
        $orden = 'position';
      }
      /* header("HTTP/1.1 402 ERROR");
        echo '{"error":"'.$list.'"}';
  return; */
      foreach ($list as $item) {

        $insertA = "";
        $insertB = "";
        $UPDATE = "";
        $id = 0;



        foreach ($item as $campo => $valor) {
          // $update="$campo='$valor',";
          if ($campo != 'id' && $campo != 'cod' && $campo != $_GET['descartar1'] && $campo != $_GET['descartar2']) {
            /* if($campo==='variant'){
             $valor=json_decode($valor);
            } */
            if($valor===false) $valor=0;
            if($valor===true) $valor=1;
            $insertA .= $campo . ',';
            $insertB .= "'$valor',";
            $UPDATE .= "$campo='$valor',";
          } else if ($campo == 'id') {
            $id = $valor;
          }
        }
        
        /* header("HTTP/1.1 401 OK");
      echo '{"error":"' . $insertA .'*'.$insertB. '"}';
      //echo '{"error":"nada"}';
      return; */
        
        $now = time();

        if ($id == 0 or $id > 1000000) {
          ///Nuevo Registro
          $rA = $insertA . 'cod';
          $rB = $insertB . "'$cod'";

          $action = "INSERT INTO $folder ($rA) VALUES ($rB)";
          $mysqli->query($action);
          $error = $mysqli->error;
          ///
          $resultID = $mysqli->query("SELECT id FROM $folder WHERE cod='$cod' ORDER BY id DESC LIMIT 1");
          $error = $mysqli->error;
          $rowID = $resultID->fetch_array();
          $id = $rowID['id'];
        } else {
          ///actualizar
          $u = $UPDATE . "cod='$cod'";
          $action = "UPDATE $folder SET $u WHERE id='$id'";
          $mysqli->query($action);
          $error = $mysqli->error;
        }
      }


     /*  header("HTTP/1.1 403 OK");
      echo '{"error":"' . $error . '"}';
      return; */

      //borramos los que no están
      $add = "company_id='$company_id' AND ";
      if ($_GET['campo'] != '') {
        $add .= "$columna='$columna_id' AND";
      }

      

      $mysqli->query("DELETE FROM $folder WHERE $add cod!='$cod'");
      $error = $mysqli->error;
      //
/*  header("HTTP/1.1 400 ERROR");
      echo '{"error":"'.$action.'"}';
return; */
      if ($data['respuesta'] == 'basica') {
        header("HTTP/1.1 200 OK");
        //echo '{"save":"ok:'.$u.'"}';
        echo '{"save":"ok"}';
      } else {
        $result = $mysqli->query("SELECT * FROM $folder WHERE $add cod='$cod' ORDER BY $orden");
        $error = $mysqli->error;
        $response = array();
        $pt = 0;
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          $pt++;
          $row['position'] = $pt;
          $response[] = $row;
        }
        ///
        header("HTTP/1.1 200 OK");
        echo json_encode($response);
      }
    }


    //$result->close();
    //$mysqli->close();

  } else if ($ref == 'save-list-list') {

    $user_id = $data['user_id'];
    $time_life = $data['time_life'];
    $token = $data['token'];

    $tokenBase = md5($user_id . $time_life . $keyEncrypter);

    if ($tokenBase != $token) {
      header("HTTP/1.1 403 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"error in token"}]';
    } else {
      /// se procesa
      $folder = $_GET['folder'];
      $company_id = $data['company_id'];
      $list = $data['list'];
      $cod = time();
      foreach ($list as $requestB) {

        foreach ($requestB as $request) {
          $insertA = "";
          $insertB = "";
          $UPDATE = "";
          $id = 0;
          foreach ($request as $campo => $valor) {
            // $update="$campo='$valor',";
            if ($campo != 'id') {
              $insertA .= $campo . ',';
              $insertB .= "'$valor',";
              $UPDATE .= "$campo='$valor',";
            } else {
              $id = $valor;
            }
          }


          $now = time();

          if ($id == 0 or $id > 1000000) {
            ///Nuevo Registro
            $rA = $insertA . 'cod';
            $rB = $insertB . "'$cod'";

            $action = "INSERT INTO $folder ($rA) VALUES ($rB)";
            $mysqli->query($action) or die($mysqli->error);
            ///
            $resultID = $mysqli->query("SELECT id FROM $folder WHERE cod='$cod' ORDER BY id DESC LIMIT 1") or die($mysqli->error);
            $rowID = $resultID->fetch_array();
            $id = $rowID['id'];
          } else {
            ///actualizar
            $u = $UPDATE . "cod='$cod'";
            $action = "UPDATE $folder SET $u WHERE id='$id'";
            $mysqli->query($action) or die($mysqli->error);
          }
        }
      }
      //header("HTTP/1.1 200 OK");
      //echo '+'.$action.'*';

      //borramos los que no están
      $mysqli->query("DELETE FROM $folder WHERE company_id='$company_id' AND cod!='$cod'") or die($mysqli->error);
      //
      //echo "SELECT * FROM $folder WHERE hote_id='$company_id' AND cod='$cod' <br>";
      $response = array();


      /* */
      ///
      header("HTTP/1.1 200 OK");
      //echo $fecha.'*'.$empresa_id;
      echo json_encode($response);
      //echo trim($update,',');
      //echo '[{"ok":"yess"}]';
    }


    //$result->close();
    //$mysqli->close();

  } else if ($ref == 'save-options') {

    $user_id = $data['user_id'];
    $time_life = $data['time_life'];
    $token = $data['token'];

    $tokenBase = md5($user_id . $time_life . $keyEncrypter);

    if ($tokenBase != $token) {
      header("HTTP/1.1 202 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"Error in Token"}]';
    } else {
      /// se procesa
      $cod = time();
      $a = 0;
      $datos = '';
      foreach ($data['listOptions'] as $option) {
        $a++;
        $m_id = $option['id'];
        $m_product_id = $option['product_id'];
        $m_name = $option['name'];
        $m_image = $option['image'];
        $m_price = $option['price'];
        $m_stock = $option['stock'];
        $m_position = $option['position'];
        $m_active = $option['active'];


        if ($m_id > 1000000 || $m_id == 0) {
          ///Nuevo Registro
          $mysqli->query("INSERT INTO maker_product_versions (product_id, `name`,	`image`,	price,	stock,	position,	active, cod) VALUES ('$m_product_id','$m_name','$m_image', '$m_price',	'$m_stock','$m_position','$m_active','$cod')") or die($mysqli->error);
        } else {
          ///actualizar
          $mysqli->query("UPDATE maker_product_versions SET product_id='$m_product_id', `name`='$m_name',	`image`='$m_image',	price='$m_price',	stock='$m_stock',	position='$m_position',active='$m_active',cod='$cod' WHERE id='$m_id'") or die($mysqli->error);
        }
      }
      /// borramos los que no están
      $mysqli->query("DELETE FROM maker_product_versions WHERE product_id='$m_product_id' AND cod!='$cod'") or die($mysqli->error);
      //
      /*
      $response = array();

      $result = $mysqli->query("SELECT * FROM maker_product_versions WHERE product_id='$product_id' ORDER BY active DESC, position ASC ");
      $c = 0;
      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $c++;
        if ($row['active'] == 0) {
          $row['active'] = false;
        } else {
          $row['active'] = true;
        }

        $response[] = $row;
      }
*/

      ///
      header("HTTP/1.1 200 OK");

      echo '[{"update":"versions ok"}]';

      //echo '[{"ok":"yess"}]';
    }
  } else if ($ref == 'upload') { /// for pages
    $user_id = $_POST['user_id'];
    $time_life = $_POST['time_life'];
    $token = $_POST['token'];
    $tokenBase = md5($user_id . $time_life . $keyEncrypter);
    //

    //$_FILE['uploadFile']
    if ($tokenBase != $token) {
      header("HTTP/1.1 202 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      //echo '[{"error":"In token: '.$tokenBase.'!='.$token.'"}]';
      echo '[{"error":"In token"}]';
    } else {
      ///

      $id = $_POST['id'];
      $position = $_POST['position'];
      $folder = $_GET['folder'];
      $prefix = $_GET['prefix'];
      $w = 1600;
      $h = 720;

      $table = $folder;

      if ($folder == 'maker_pages') {
        $table = 'maker_content_blocks';
        $resultM = $mysqli->query("SELECT maker_menu.type FROM maker_content_blocks,maker_menu WHERE maker_content_blocks.id='$id' AND maker_menu.id=maker_content_blocks.menu_id LIMIT 1") or die($mysqli->error);
        $rowM = $resultM->fetch_array(MYSQLI_ASSOC);
        $type = $rowM['type'];
        $w = 1600;
        $h = 635;
        if ($type == 'News/Events') { //if($type=='Gallery' || $type=='News/Events' ){
          $w = 800;
          $h = 600;
        }
      } else if ($folder == 'maker_products' && $prefix == 'C') {
        $table = 'maker_categories';
        $w = 1600;
        $h = 635;
      } else if ($folder == 'maker_products') {
        $table = 'maker_products';
        $w = 370;
        $h = 520;
      } else if ($folder == 'maker_gallery' || $folder == 'maker_product_versions' || $folder == 'maker_companies') {
        $w = 800;
        $h = 600;
      }

      if ($id > 1000000 && $folder == 'maker_gallery') {
        $content_id = $_POST['content_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $mysqli->query("INSERT INTO $table (content_id, title, `description`, position) VALUES ('$content_id', '$title', '$description', '$position')") or die($mysqli->error);
        $result = $mysqli->query("SELECT id FROM $table WHERE content_id='$content_id' AND position='$position' ORDER BY id DESC LIMIT 1") or die($mysqli->error);
        $row = $result->fetch_array();
        $id = $row['id'];
      }
      if ($id > 1000000 && $folder == 'maker_product_versions') {
        $product_id = $_POST['product_id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $active = $_POST['active'];

        $mysqli->query("INSERT INTO $table (product_id, `name`, price, stock,position,active) VALUES ('$product_id', '$name', '$price','$stock', '$position','$active')") or die($mysqli->error);
        $result = $mysqli->query("SELECT id FROM $table WHERE product_id='$product_id' ORDER BY id DESC LIMIT 1") or die($mysqli->error);
        $row = $result->fetch_array();
        $id = $row['id'];
      }

      if ($folder == 'maker_gallery' || $folder == 'maker_product_versions') {
        $position = '';
      }


      $wM = ($w / 2);
      $hM = ($h / 2);
      /// VErot Upload
      $error = '';
      if ($_FILES['uploadFile']['name'] != '') {
        if ($_FILES['uploadFile']['type'] != "image/pjpeg" and $_FILES['uploadFile']['type'] != "image/jpeg" and $_FILES['uploadFile']['type'] != "image/png") {
          $error = 'Only accept JPG or PNG';
        } else {
          //
          $path = $_FILES['uploadFile']['name'];
          $ext = pathinfo($path, PATHINFO_EXTENSION);
          $ext = strtolower($ext);
          //
          if ($position != '') {
            $path_preview = $prefix . $id . '_' . $position . '.' . $ext . '?t=' . time();
            $path_base = $prefix . $id . '_' . $position;
          } else {
            $path_preview = $prefix . $id . '.' . $ext . '?t=' . time();
            $path_base = $prefix . $id;
          }

          //
          include('verot-upload/src/class.upload.php');
          ini_set("max_execution_time", 0);
          $handle = new \Verot\Upload\Upload($_FILES['uploadFile']);
          if ($handle->uploaded) {
            ///PC version
            $handle->image_resize          = true;
            $handle->file_new_name_body = $path_base;
            $handle->file_overwrite = true;

            if ($ext == 'png') {
              $handle->png_compression       = 0;
            } else {
              $handle->jpeg_quality          = 90;
            }

            $handle->image_x               = $w;
            $handle->image_y               = $h;
            $handle->image_ratio_crop       = true;

            $handle->process('../maker-files/images/' . $folder . '/');
            /// Movil Version
            $handle->image_resize          = true;
            $handle->file_new_name_body = 'M' . $path_base;
            $handle->file_overwrite = true;

            if ($ext == 'png') {
              $handle->png_compression       = 0;
            } else {
              $handle->jpeg_quality          = 90;
            }

            $handle->image_x               = $wM;
            $handle->image_y               = $hM;
            $handle->image_ratio_crop       = true;

            $handle->process('../maker-files/images/' . $folder . '/');
          } else {
            ///error
            $error = 'si';
          }
        }
      }
      /// The End Verot;

      if ($error == '') {
        //
        $row_name = 'image' . $position;


        $action = "UPDATE $table SET `$row_name`='$path_preview' WHERE id='$id'";
        $mysqli->query($action) or die($mysqli->error);
        //
        $result = $mysqli->query("SELECT * FROM $table WHERE id='$id' LIMIT 1") or die($mysqli->error);
        $new_cont = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          //$row['ruta']=$path_preview;
          $new_cont[] = $row;
        }
        // 

        header("HTTP/1.1 200 OK");
        //echo '[{"ruta":"'.$path_preview.'"}]'; 
        echo json_encode($new_cont);
      } else {
        header("HTTP/1.1 402 OK");
        echo '[{"error":"' . $error . '"}]';
      }
    }
  } else if ($ref == 'upload-product') { /// for Product

    $user_id = $_POST['user_id'];
    $time_life = $_POST['time_life'];
    $token = $_POST['token'];
    $tokenBase = md5($user_id . $time_life . $keyEncrypter);
    //
    $id = $_POST['id'];
    $position = $_POST['position'];
    //$_FILE['uploadFile']
    if ($tokenBase != $token) {
      header("HTTP/1.1 202 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"In token"}]';
    } else {
      /// type


      $h = 600;
      $hM = $h / 2;

      /// VErot Upload
      $error = '';
      if ($_FILES['uploadFile']['name'] != '') {
        if ($_FILES['uploadFile']['type'] != "image/pjpeg" and $_FILES['uploadFile']['type'] != "image/jpeg" and $_FILES['uploadFile']['type'] != "image/png") {
          $error = 'Only accept JPG or PNG';
        } else {
          //
          $path = $_FILES['uploadFile']['name'];
          $ext = pathinfo($path, PATHINFO_EXTENSION);
          $ext = strtolower($ext);
          //
          $path_preview = $id . '_' . $position . '.' . $ext . '?t=' . time();
          //
          include('verot-upload/src/class.upload.php');
          ini_set("max_execution_time", 0);
          $handle = new \Verot\Upload\Upload($_FILES['uploadFile']);
          if ($handle->uploaded) {
            ///PC version
            $handle->image_resize          = true;
            $handle->file_new_name_body = $id . '_' . $position;
            $handle->file_overwrite = true;

            $handle->image_ratio_x         = true;
            $handle->image_y               = $h;

            $handle->process('../maker-files/images/maker_products/');
            /// Movil Version
            $handle->image_resize          = true;
            $handle->file_new_name_body = 'M' . $id . '_' . $position;
            $handle->file_overwrite = true;

            $handle->image_ratio_x         = true;
            $handle->image_y               = $hM;

            $handle->process('../maker-files/images/maker_products/');
          } else {
            ///error
            $error = 'si';
          }
        }
      }
      /// The End Verot 


      if ($error == '') {
        //
        $row_name = 'image' . $position;

        $action = "UPDATE maker_products SET `$row_name`='$path_preview' WHERE id='$id'";
        $mysqli->query($action) or die($mysqli->error);
        //
        $result = $mysqli->query("SELECT * FROM maker_products WHERE id='$id' LIMIT 1");
        $new_prod = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          //$row['ruta']=$path_preview;
          $new_prod[] = $row;
        }
        //  
        header("HTTP/1.1 200 OK");
        //echo '[{"ruta":"'.$path_preview.'"}]'; 
        echo json_encode($new_prod);
      } else {
        header("HTTP/1.1 202 OK");
        echo '[{"error":"' . $error . '"}]';
      }
    }
  } else if ($ref == 'upload-category') { /// for Product

    $user_id = $_POST['user_id'];
    $time_life = $_POST['time_life'];
    $token = $_POST['token'];
    $tokenBase = md5($user_id . $time_life . $keyEncrypter);
    //
    $id = $_POST['id'];
    $position = $_POST['position'];
    //$_FILE['uploadFile']
    if ($tokenBase != $token) {
      header("HTTP/1.1 202 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"In token"}]';
    } else {
      /// type

      $w = 1600;
      $wM = $w / 2;

      /// VErot Upload
      $error = '';
      if ($_FILES['uploadFile']['name'] != '') {
        if ($_FILES['uploadFile']['type'] != "image/pjpeg" and $_FILES['uploadFile']['type'] != "image/jpeg" and $_FILES['uploadFile']['type'] != "image/png") {
          $error = 'Only accept JPG or PNG';
        } else {
          //
          $path = $_FILES['uploadFile']['name'];
          $ext = pathinfo($path, PATHINFO_EXTENSION);
          $ext = strtolower($ext);
          //
          $path_preview = 'C' . $id . '.' . $ext . '?t=' . time();
          //
          include('verot-upload/src/class.upload.php');
          ini_set("max_execution_time", 0);
          $handle = new \Verot\Upload\Upload($_FILES['uploadFile']);
          if ($handle->uploaded) {
            ///PC version
            $handle->image_resize          = true;
            $handle->file_new_name_body = 'C' . $id;
            $handle->file_overwrite = true;

            $handle->image_ratio_y         = true;
            $handle->image_x               = $w;

            $handle->process('../maker-files/images/maker_products/');
            /// Movil Version
            $handle->image_resize          = true;
            $handle->file_new_name_body = 'MC' . $id;
            $handle->file_overwrite = true;

            $handle->image_ratio_y         = true;
            $handle->image_x               = $wM;

            $handle->process('../maker-files/images/maker_products/');
          } else {
            ///error
            $error = 'si';
          }
        }
      }
      /// The End Verot 


      if ($error == '') {
        //

        $action = "UPDATE maker_categories SET `image`='$path_preview' WHERE id='$id'";
        $mysqli->query($action) or die($mysqli->error);
        //
        $result = $mysqli->query("SELECT * FROM maker_categories WHERE id='$id' LIMIT 1");
        $new_cat = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          //$row['ruta']=$path_preview;
          $new_cat[] = $row;
        }
        //  
        header("HTTP/1.1 200 OK");
        //echo '[{"ruta":"'.$path_preview.'"}]'; 
        echo json_encode($new_cat);
      } else {
        header("HTTP/1.1 202 OK");
        echo '[{"error":"' . $error . '"}]';
      }
    }
  } else if ($ref == 'save-formWeb') {

    $company_id = $_GET['company_id'];
    $tokenWeb = $_GET['tokenWeb'];

    $tokenB = md5($company_id . $keyEncrypter);

    if ($tokenWeb != $tokenB) {
      header("HTTP/1.1 403 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"error in token"}]';
    } else {
      $page = $data['page'];
      $request = '';
      $a = 0;
      foreach ($data['listForm'] as $f) {
        foreach ($f as $row => $value) {
          if ($row == 'name') {
            $a++;
            $request .= $a . '. <strong>' . $value . ':</strong> ';
          }
          if ($row == 'response') {
            $request .= $value . '<br>';
          }

          //
        }
      }

      /*
 header("HTTP/1.1 202 ERROR");
 echo '[{"error":"'.$request.'"}]';
 return;
 */
      //echo 'Z:'+$request;
      $now = date('Y-m-d H:i:s');

      ///Nuevo Registro
      $mysqli->query("INSERT INTO maker_forms_received (company_id,`page`,`date`,request) VALUES ('$company_id','$page','$now','$request')") or die($mysqli->error);



      ///
      header("HTTP/1.1 200 OK");

      echo '[{"ok":"Form Send: ' . $data['sendTo'] . '"}]';

      /// send
      if ($data['sendTo'] != '') {
        $rCi = $mysqli->query("SELECT company FROM maker_companies WHERE id='$company_id' LIMIT 1 ") or die($mysqli->error);
        $rowCi = $rCi->fetch_array(MYSQLI_ASSOC);
        $company = $rowCi['company'];


        $message = '<strong>' . $now . '</strong><br>Web Page: ' . $page . '<br><br><br>' . $request;
        ob_start();
        include("mail.php");
        $html = ob_get_contents();
        ob_end_clean();

        $subjet = $page . ' Form';


        $from_email = $data['sendTo'];
        // echo $html;
        $send_email = $data['sendTo'];
        if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
          $eol = "\r\n";
        } elseif (strtoupper(substr(PHP_OS, 0, 3) == 'MAC')) {
          $eol = "\r";
        } else {
          $eol = "\n";
        }
        $header = "Content-type: text/html" . $eol;
        //dirección del remitente
        $header .= 'From: ' . $company . ' <' . $from_email . '>' . $eol;
        $header .= 'Reply-To: ' . $company . ' <' . $from_email . '>' . $eol;
        $header .= "Message-ID:<" . time() . " TheSystem@" . $_SERVER['SERVER_NAME'] . ">" . $eol;
        $header .= "X-Mailer: PHP v" . phpversion() . $eol;
        $header .= 'MIME-Version: 1.0' . $eol;
        //////
        mail($send_email, $subjet, $html, $header);
      }
      //the end send

    }
  } else if ($ref == 'pedidoSave') {
    $company_id = $data['company_id'];
    $tokenWeb = $data['tokenWeb'];
    $tokenBase = md5($company_id . $keyEncrypter);

    if ($tokenBase != $tokenWeb) {
      header("HTTP/1.1 202 ERROR");
      //echo '[{"error":"yess-'.$user_id.'+'.$time_life.'"}]'; 
      echo '[{"error":"error in token"}]';
    } else {
      /// se procesa primero el comprador
      $cod = time();
      $folder = 'maker_buyer';
      $request = $data['pedidoComprador'];
      $insertA = "";
      $insertB = "";
      $update = "";
      $id = 0;
      $email_destino = $request['email'];
      foreach ($request as $campo => $valor) {
        // $update="$campo='$valor',";
        if ($campo != 'id') {
          $insertA .= $campo . ',';
          $insertB .= "'$valor',";
          $update .= "$campo='$valor',";
        } else {
          $id = $valor;
        }
      }


      /* header("HTTP/1.1 200 OK");
            echo '[{"error":"prueba"}]';
            return; */



      $now = time();

      if ($id == 0 or $id > 1000000) {
        ///Nuevo Registro
        $rA = trim($insertA, ',');
        $rB = trim($insertB, ',');

        $action = "INSERT INTO $folder ($rA) VALUES ($rB)";
        $mysqli->query($action) or die($mysqli->error);
        ///
        $resultID = $mysqli->query("SELECT id FROM $folder WHERE company_id='$company_id' ORDER BY id DESC LIMIT 1") or die($mysqli->error);
        $rowID = $resultID->fetch_array();
        $id = $rowID['id'];
      } else {
        ///actualizar
        $u = trim($update, ',');
        $action = "UPDATE $folder SET $u WHERE id='$id' AND company_id='$company_id'";
        $mysqli->query($action) or die($mysqli->error);
      }


      $folder = 'maker_orders';
      /// definimos el id del pedido
      $rPID = $mysqli->query("SELECT order_id FROM $folder WHERE company_id='$company_id' ORDER BY id DESC LIMIT 1") or die($mysqli->error);
      $rowPID = $rPID->fetch_array();
      $order_id = 1;
      if ($rowPID['order_id'] != '') {
        $order_id = $rowPID['order_id'] + 1;
      }
      ///------ el pedido

      $comprador_id = $id;
      $request = $data['pedido'];
      $insertA = "";
      $insertB = "";
      $update = "";
      $id = 0;
      foreach ($request as $campo => $valor) {
        // $update="$campo='$valor',";
        if ($campo == 'comprador_id') {
          $valor = $comprador_id;
        }
        if ($campo == 'order_id' && $valor == 0) {
          $valor = $order_id;
        }
        if ($campo == 'fecha' && ($valor == '' || $valor = '0000-00-00 00:00:00')) {
          $valor = date('Y-m-d H:i:s');
        }
        if ($campo == 'productos') {
          //$valor = json_decode($valor, true);
          $productos = $data['productos'];

          $pp = '';

          foreach ($productos as $producto) {
            $insertAP = "";
            $insertBP = "";

            $pp .= '<tr>';
            $restar = $producto['quantity'];
            $pd_id = $producto['product_id'];

            $pd_version = $producto['version'];

            if ($producto['version'] == 'única') {
              $rSK = $mysqli->query("SELECT stock FROM maker_products WHERE id='$pd_id' LIMIT 1") or die($mysqli->error);
              $rowSK = $rSK->fetch_array();
              $stock = $rowSK['stock'];

              $newStock = $stock - $restar;

              $query = "UPDATE maker_products SET stock= '$newStock' WHERE id='$pd_id'";
              $mysqli->query($query) or die($mysqli->error);
            } else {
              $rSK = $mysqli->query("SELECT id, stock FROM maker_product_versions WHERE product_id='$pd_id' and `name`='$pd_version' LIMIT 1") or die($mysqli->error);
              $rowSK = $rSK->fetch_array();
              $stock = $rowSK['stock'];

              $newStock = $stock - $restar;
              $pd_id = $rowSK['id'];
              $query = "UPDATE maker_product_versions SET stock= '$newStock' WHERE id='$pd_id'";
              $mysqli->query($query) or die($mysqli->error);
            }




            if ($producto['version'] != 'única') {
              $producto['name'] = $producto['name'] . '<br>Versión: ' . $producto['version_type'] . ' ' . $producto['version'];
            }
            /*  
header("HTTP/1.1 200 OK");
            echo '[{"error":"' . $producto['name'] . '"}]';
            return;
*/
            foreach ($producto as $prod => $v) {

              if ($prod != 'id') {
                $insertAP .= $prod . ',';
                $insertBP .= "'$v',";
                if ($prod == 'image') {
                  $pp .= '<td style="border-bottom:solid 1px black"><img src="https://maker.cityciudad.com/maker-files/images/' . $v . '" alt="producto" width="120" height="90"></td>';
                } else if ($prod == 'name' || $prod == 'price' || $prod == 'total') {
                  if ($v > 1000) {
                    $pp .= '<td style="border-bottom:solid 1px black">' . number_format($v, 0, ',', '.') . '</td>';
                  } else {
                    $pp .= '<td style="border-bottom:solid 1px black">' . $v . '</td>';
                  }
                } else if ($prod == 'quantity') {

                  $pp .= '<td style="border-bottom:solid 1px black; text-align:center">' . number_format($v, 0, ',', '.') . '</td>';
                }
              }
            }

            $pp .= '</tr>';
            $insertAP .= 'cod';
            $insertBP .= "'$cod'";
            //

            /* header("HTTP/1.1 200 OK");
            echo '[{"error":"'.$insertAP.'**'.$insertBP.'"}]';
            return; */

            $action = "INSERT INTO maker_order_product ($insertAP) VALUES ($insertBP)";
            $mysqli->query($action) or die($mysqli->error);
          }
        }
        if ($campo != 'id') {
          $insertA .= $campo . ',';
          $insertB .= "'$valor',";
          $update .= "$campo='$valor',";
        } else {
          $id = $valor;
        }
      }


      $now = time();

      if ($id == 0 or $id > 1000000) {
        ///Nuevo Registro
        $rA = trim($insertA, ',');
        $rB = trim($insertB, ',');
        //

        /* header("HTTP/1.1 200 OK");
        echo '[{"error":"'.$rA.'"}]';
        return; */

        $action = "INSERT INTO $folder ($rA) VALUES ($rB)";
        $mysqli->query($action) or die($mysqli->error);
        ///
        $resultID = $mysqli->query("SELECT id FROM $folder WHERE company_id='$company_id' ORDER BY id DESC LIMIT 1") or die($mysqli->error);
        $rowID = $resultID->fetch_array();
        $id = $rowID['id'];
        //
        //actualizo el id de la order de los productos
        $action = "UPDATE maker_order_product SET order_id='$id' WHERE cod='$cod' AND company_id='$company_id'";
        $mysqli->query($action) or die($mysqli->error);
      } else {
        ///actualizar
        $u = trim($update, ',');
        $action = "UPDATE $folder SET $u WHERE id='$id' AND company_id='$company_id'";
        $mysqli->query($action) or die($mysqli->error);
      }

      $result = $mysqli->query("SELECT * FROM $folder WHERE id='$id' AND company_id='$company_id' LIMIT 1");
      $response = array();
      $row = $result->fetch_array(MYSQLI_ASSOC);

      $response[] = $row;


      /// eniar correo de aviso
      //remitente:
      $rRM = $mysqli->query("SELECT * FROM maker_companies WHERE id='$company_id' LIMIT 1") or die($mysqli->error);
      $rowRM = $rRM->fetch_array();
      $remitente = $rowRM['email'];
      //comprador
      $rCP = $mysqli->query("SELECT * FROM maker_buyer WHERE company_id='$company_id' AND id='$comprador_id' LIMIT 1") or die($mysqli->error);
      $rowCP = $rCP->fetch_array();
      $cuentas_bancarias = 'Contactenos por favor';
      if ($rowRM['bank1'] != '') {
        $cuentas_bancarias = $rowRM['bank1'];
      }
      if ($rowRM['bank2'] != '') {
        $cuentas_bancarias .= '<br>' . $rowRM['bank2'];
      }
      if ($rowRM['bank3'] != '') {
        $cuentas_bancarias .= '<br>' . $rowRM['bank3'];
      }
      //send mail
      $message = '<div style="text-align:center"><img src="https://maker.cityciudad.com/maker-files/images/maker_companies/M' . $rowRM['image1'] . '" alt="logo ' . $rowRM['company'] . '" width="150" height="112"></div><br><strong>Pedido WEB: ' . $id . '</strong><br>' . $rowRM['company'] . ' ' . $rowRM['documento'] . '<br>' . $rowRM['country'] . '-' . $rowRM['city'] . '<br>' . $rowRM['address'] . '<br><br><strong>Medio de Pago:</strong><br>' . $cuentas_bancarias . '<br><br><strong>Comprador:</strong><br>' . $rowCP['nombres'] . ' ' . $rowCP['apellidos'] . '<br>Documento:' . $rowCP['documento'] . ' | Celular:' . $rowCP['celular'] . '<br>Email: ' . $rowCP['email'] . '<br><br><strong>Destino Envío:</strong><br>' . $rowCP['pais'] . ' - ' . $rowCP['ciudad'] . '<br>' . $rowCP['direccion'] . '<br><br><strong>Compra:</strong><br><table style="border:solid 1px black; width: 100%"><tr ><td style="border-bottom:solid 1px black">Producto</td><td style="border-bottom:solid 1px black">Imagen</td><td style="border-bottom:solid 1px black">Precio</td><td style="border-bottom:solid 1px black">Cantidad</td><td style="border-bottom:solid 1px black">Total</td></tr>' . $pp . '<tr><td></td><td></td><td></td><td>Total</td><td><strong>$' . number_format($request['valor'], 0, ',', '.') . '</strong></td></tr></table>';

      ob_start();
      include("mail.php");
      $html = ob_get_contents();
      ob_end_clean();

      $subjet = 'Pedido N.' . $order_id;


      $from_email = $remitente;
      // echo $html;
      $send_email = $email_destino;

      if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
        $eol = "\r\n";
      } elseif (strtoupper(substr(PHP_OS, 0, 3) == 'MAC')) {
        $eol = "\r";
      } else {
        $eol = "\n";
      }
      $header = "Content-type: text/html" . $eol;
      //dirección del remitente
      $header .= 'From: ' . $company . ' <' . $from_email . '>' . $eol;
      $header .= 'Reply-To: ' . $company . ' <' . $from_email . '>' . $eol;
      $header .= "Message-ID:<" . time() . " TheSystem@" . $_SERVER['SERVER_NAME'] . ">" . $eol;
      $header .= "X-Mailer: PHP v" . phpversion() . $eol;
      $header .= 'MIME-Version: 1.0' . $eol;
      //////
      mail($send_email, $subjet, $html, $header);
      mail($remitente, $subjet, $html, $header);
      //mail('diegosierra@cityciudad.com', $subjet, $html, $header);
      /// the end send mail

      ///
      header("HTTP/1.1 200 OK");
      //echo $fecha.'*'.$empresa_id;
      echo '[{"pedido":"ok"}]';
    }


    //$result->close();
    //$mysqli->close();


  }
}

/// FIN metodo POST

else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
  $ref == $_GET['ref'];

  if ($ref == 'test') {

    header("HTTP/1.1 200 OK");
    echo '[{"id":' . $data['a'] . ',"documento":"' . $data['b'] . '","nombre":"' . $data['c'] . '-' . $_SERVER['REQUEST_METHOD'] . '"},{"id":' . ($data['a'] + 1) . ',"documento":"KP' . $data['b'] . '","nombre":"KP' . $data['c'] . '-' . $_SERVER['REQUEST_METHOD'] . '"}]';
  } else
    if ($ref == 'buyer') {

    $id = $data['id'];
    $empresa_id = $data['empresa_id'];
    $documento = $data['documento'];
    $nombre = $data['nombre'];
    $apellido = $data['apellido'];
    $celular = $data['celular'];
    $email = $data['email'];
    $ciudad = $data['ciudad'];
    $barrio = $data['barrio'];
    $direccion = $data['direccion'];
    $publicidad = $data['publicidad'];


    if ($id != 0) {
      $mysqli->query("UPDATE maker_compradores SET documento='$documento',nombre='$nombre', apellido='$apellido', celular='$celular', email='$email', ciudad='$ciudad', barrio='$barrio', direccion='$direccion', publicidad='$publicidad' WHERE id='$id'") or die($mysqli->error);
      header("HTTP/1.1 200 OK");
      echo $id;
    } else {
      $mysqli->query("INSERT INTO maker_compradores (empresa_id, documento,nombre, apellido, celular, email, ciudad, barrio, direccion, publicidad) VALUES('$empresa_id', '$documento', '$nombre', '$apellido', '$celular', '$email', '$ciudad', '$barrio', '$direccion', '$publicidad')") or die($mysqli->error);
      $result = $mysqli->query("SELECT id FROM maker_compradores WHERE documento='$documento' LIMIT 1") or die($mysqli->error);
      $row = $result->fetch_array();
      header("HTTP/1.1 200 OK");
      echo $row['id'];
      $result->close();
    }


    //$mysqli->close();

  }
}
/// FIN metodo PUT

else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
  $ref = $_REQUEST['ref'];
  //echo $_REQUEST['tipo'].'++'.$_GET['ref'];
  //echo $tipo.'--'.$_SERVER['REQUEST_METHOD'].'++'.$_REQUEST['tipo'];
  if ($ref == 'test') {

    header("HTTP/1.1 200 OK");
    echo '[{"id":' . $data['a'] . ',"documento":"' . $data['b'] . '","nombre":"' . $data['c'] . '-' . $_SERVER['REQUEST_METHOD'] . '"},{"id":' . ($data['a'] + 1) . ',"documento":"KP' . $data['b'] . '","nombre":"KP' . $data['c'] . '-' . $_SERVER['REQUEST_METHOD'] . '"}]';
  } else
if ($ref == 'delete-factura') {
    $factura = $_REQUEST['id'];
    $comercio = $_REQUEST['comercio'];
    $comprador = $_REQUEST['comercio'];

    $mysqli->query("DELETE facturas WHERE comercio_id='$comercio' AND comprador_id='$comprador' AND factura='$factura' ") or die($mysqli->error);
    header("HTTP/1.1 200 OK");
    echo '{"resultado":"borrado"}';
  }
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
//
else {
  header("HTTP/1.1 202 Error");
}
