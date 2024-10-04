<div class="main-container" style="background-color: rgba(44, 44, 44, 0.8); padding: 20px; border-radius: 10px;; margin: auto; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);">

    <form class="box login" action="" method="POST" autocomplete="off" style="background-color: #2c2c2c; padding: 40px; border-radius: 10px;">
        <p class="has-text-centered">
            <i class="fas fa-user-circle fa-5x" style="color: #ffa974;"></i>
        </p>
        <h5 class="title is-5 has-text-centered" style="color: #ffa974;">Inicia sesión con tu cuenta</h5>

        <?php
            if(isset($_POST['login_usuario']) && isset($_POST['login_clave'])){
                $insLogin->iniciarSesionControlador();
            }
        ?>

        <label style="color: #ff8c42;">SUCURSAL</label><br>
        <div class="select mb-5">
            <select name="sucursal" style="background-color: #2c2c2c; color: #fff; border: 1px solid #ff8c42;">
                <?php
                    $datos_sucursal=$insLogin->seleccionarDatos("Normal","sucursal","*",0);

                    while($campos_sucursal=$datos_sucursal->fetch()){
                        echo '<option value="'.$campos_sucursal['id_sucursal'].'">'.$campos_sucursal['sucursal_descripcion'].'</option>';
                    }
                ?>
            </select>
        </div>  

        <div class="field">
            <label class="label" style="color: #ff8c42;"><i class="fas fa-user-secret"></i> &nbsp; Usuario</label>
            <div class="control">
                <input class="input" type="text" name="login_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required style="background-color: #2c2c2c; color: #fff; border: 1px solid #ff8c42;">
            </div>
        </div>

        <div class="field">
            <label class="label" style="color: #ff8c42;"><i class="fas fa-key"></i> &nbsp; Clave</label>
            <div class="control">
                <input class="input" type="password" name="login_clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required style="background-color: #2c2c2c; color: #fff; border: 1px solid #ff8c42;">
            </div>
        </div>

        <p class="has-text-centered mb-4 mt-3">
            <button type="submit" class="button is-info is-rounded" style="background-color: #ffa974; border-color: #ffa974; color: #2c2c2c;">LOG IN</button>
        </p>

    </form>
</div>
