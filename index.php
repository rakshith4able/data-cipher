<?php

$ALGORITHM = 'AES-128-CBC';
$IV    = '12dasdq3g5b2434b';

$error = '';

if (isset($_POST) && isset($_POST['action'])) {

  $password   = isset($_POST['password']) && $_POST['password']!='' ? $_POST['password'] : null;
  $action = isset($_POST['action']) && in_array($_POST['action'],array('c','d')) ? $_POST['action'] : null;
  $file     = isset($_FILES) && isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK ? $_FILES['file'] : null;

  if ($password === null) {
    $error .= 'Invalid Password<br>';
  }
  if ($action === null) {
    $error .= 'Invalid Action<br>';
  }
  if ($file === null) {
    $error .= 'Errors occurred while elaborating the file';
    echo "<script>alert('".$error. "')</script>";


  }

  if ($error === '') {

    $contenuto = '';
    $nomefile  = '';

    $contenuto = file_get_contents($file['tmp_name']);
    $filename  = $file['name'];

    switch ($action) {
      case 'c':
        $contenuto = openssl_encrypt($contenuto, $ALGORITHM, $password, 0, $IV);
        $filename  = $filename . '.crypto';
        break;
      case 'd':
        $contenuto = openssl_decrypt($contenuto, $ALGORITHM, $password, 0, $IV);
        $filename  = preg_replace('#\.crypto$#','',$filename);
        break;
    }

    if ($contenuto === false) {
      $error .= 'Errors occurred while encrypting/decrypting the file ';
      }

    if ($error === '') {

      header("Pragma: public");
      header("Pragma: no-cache");
      header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Expires: 0");
      header("Content-Type: application/octet-stream");
      header("Content-Disposition: attachment; filename=\"" . $filename . "\";");
      $size = strlen($contenuto);
      header("Content-Length: " . $size);
      echo $contenuto;
      die;

    }
    else
    {
        echo "<script>alert('Invalid Password') </script>";

    }


  }


}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="app.css">
    <title>Data Cipher | By Rakshiii</title>
</head>

<body>
    <header>
        <h1>Data Cipher</h1>
    </header>

    <div class="container">





        <form class="form" enctype="multipart/form-data" method="post" id="form1" auto-complete="off">
            <h2>ENCRYPT/DECRYPT A FILE</h2>


            <!-- <div class="">
                    <label for="file">FILE</label>
                <input type="file" name="file" id="file" />
            </div> -->

            <div class='file-input'>
                <input type='file' name="file" id="file">
                <span class='button'>Choose</span>
                <span class='label' data-js-label>No file selected</label>
            </div>

            <div>
                <label for=" password">PASSWORD</label>
                <input type="password" name="password" id="password" placeholder="Enter password" required />
            </div>
            <div>
                <label for="action">ACTION</label>
                <select name="action" id="action" required class="form-control">
                    <option value="">-- CHOOSE --</option>
                    <option value="c">ENCRYPT</option>
                    <option value="d">DECRYPT</option>
                </select>
            </div>
            <div>
                <button type="submit" class="submit" id="submit">EXECUTE</button>
            </div>
        </form>
    </div>
    <footer>
        <h2> Your data is your power, and it needs to be protected</h2>
    </footer>

    <script>

        var inputs = document.querySelectorAll('.file-input')

        for (var i = 0, len = inputs.length; i < len; i++) {
            customInput(inputs[i])
        }

        function customInput(el) {
            const fileInput = el.querySelector('[type="file"]')
            const label = el.querySelector('[data-js-label]')

            fileInput.onchange =
                fileInput.onmouseout = function () {
                    if (!fileInput.value) return

                    var value = fileInput.value.replace(/^.*[\\\/]/, '')
                    el.className += ' -chosen'
                    label.innerText = value
                }
        }

        const form = document.querySelector('form');
        form.addEventListener('onSubmit', (e) => { e.preventDefault(); })
    </script>
</body>

</html>