<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>

    <?php
    // Quick example of vite asset loading, only support for production mode currently
    $manifestFile = file_get_contents('/dist/.vite/manifest.json');
    $manifest = json_decode($manifestFile);
    $vite_js = $manifest["main.js"]["file"];
    ?>
    <script type="text/javascript" src="<?php echo $vite_js; ?>"></script>
</head>
<body>


<?php
// TODO: Connect to database
$db_host = $_ENV['DB_HOST'];
$db_user = $_ENV['DB_USER'];
$db_password = $_ENV['DB_PASSWORD'];
$db_name = $_ENV['DB_NAME'];
$connect = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if(!$connect){
    echo "Could not connect to DB. :(";
}else{
    echo "Sucessfully connected to the DB :+1:!";
}

// TODO: insert something each time

// TODO: upload file to assets folder / mount volume

// TODO: Include JS files from dist/ of vite

?>



    
</body>
</html>