<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MFSP Passphrase</title>
</head>

<body>
    <div>
        <h2>(MFSP Plugin) Passphrase required</h2>
        <form method="POST" action="/wp-json/msfp/v1/share/<?php echo $url_uuid; ?>" autocomplete="off">
            <label for="id_passphrase">Passphrase:</label>
            <input id="id_passphrase" type="text" name="passphrase" required>
            <input type="submit" value="Submit passphrase">

            <?php if (isset($_GET["wrong_passphrase"])) { ?>
                <p style="color:crimson;">Wrong Passphrase</p>
            <?php } ?>

        </form>
    </div>
</body>

</html>