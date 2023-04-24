<?php
require_once '_connect.php';

$pdo = new \PDO(DSN, USER, PASS);

$data = array_map('trim',$_POST);
$data = array_map('htmlentities', $data);

$errors = [];

if($_SERVER['REQUEST_METHOD'] === "POST"){ 
  $uploadDir = 'public/uploads/';
  $uploadFile = $uploadDir . basename($_FILES['avatar']['name']);
  $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
  $authorizedExtensions = ['jpg', 'png', 'gif', 'webp'];
  $maxFileSize = 1000000;
  
  if(!isset($_POST['user_name']) || trim($_POST['user_name']) === '') 
      $errors[] = "Le nom est obligatoire";
  if(!isset($_POST['user_email']) || trim($_POST['user_email']) === '') 
      $errors[] = "L'adresse mail est obligatoire";
  if( (!in_array($extension, $authorizedExtensions))){
      $errors[] = 'Veuillez sélectionner une image de type Jpg ou Jpeg ou Png !';
  }
  if( file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize)
  {
      $errors[] = "Votre fichier doit faire moins de 1M !";
  }

  if(empty($errors)) {{ 
    $query = 'INSERT INTO homer (name, email, uploadFile) VALUES (:name, :email, :uploadFile)';
        $statement = $pdo->prepare($query);
        $statement->bindValue(':name', $name, \PDO::PARAM_STR);
        $statement->bindValue(':email', $email, \PDO::PARAM_STR);
        $statement->bindValue(':uploadFile', $uploadFile, \PDO::PARAM_STR);

        $statement->execute();

      $uploadDir = 'public/uploads/';
      
      $uploadFile = $uploadDir . basename($_FILES['avatar']['name']);
  
      move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);

      header('Location: passeport.php');
    }}

}

  ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css" /></head>
<body>
<form  action=""  method="post">
    <div>
      <label  for="nom">Nom :</label>
      <input  type="text"  id="nom"  name="user_name" required>
    </div>
    <div>
      <label  for="courriel">Courriel :</label>
        <input  type="email"  id="courriel"  name="user_email" required>
    </div>
    <div>
    <label for="imageUpload">Upload an profile image</label>    
    <input type="file" name="avatar" id="imageUpload" />
    </div>
    <div>
      <!-- <label  for="phonenumber">Numéro de téléphone :</label>
      <input  type="tel"  id="number"  name="phone_number" required>
    </div> -->
    <!-- <div>
      <label  for="message">Message :</label>
      <textarea  id="message"  name="user_message" required></textarea>
    </div>
    <label for="choix-select">choisis:</label>

  <select name="theme" id="theme_select" required>
    <option value="">choix thématiques:</option>
    <option value="j">allumer</option>
    <option value="o">le</option>
    <option value="h">feu</option>
    <option value="n">allumerrrrr</option>
    <option value="n">leee</option>
    <option value="y">feuuuuu</option>
  </select> -->

    <div  class="button">
      <button  type="submit">Envoyer votre message</button>
    </div>
  </form>
  
  <?php  
      if (count($errors) > 0) : ?>
        <div class="attention">
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
<?php endif; ?>    

</body>
</html>