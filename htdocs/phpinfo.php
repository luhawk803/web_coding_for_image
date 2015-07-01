<!-- LOGIN FORM in: admin/index.php -->
<form method="post" action="#">
    <p><label for="u_name">username:</label></p>
    <p><input type="text" name="u_name" value=""></p>
    
    <p><label for="u_pass">password:</label></p>
    <p><input type="password" name="u_pass" value=""></p>
    
    <p><button type="submit" name="go">log me in</button></p>
</form>
<!-- A paragraph to display eventual errors -->
<p><strong><?php if(isset($error)){echo $error;}  ?></strong></p> 