        <?php
        $password_raw = $_POST['password'] ?? '';
        if ($password_raw === '') {
            $errors['password'] = "Password is required";
        }
        elseif (strlen($password_raw) < 8) {
            $errors['password'] = "Password must be at least 8 characters";
        }
        elseif (!preg_match('/[A-Z]/', $password_raw)) {
            $errors['password'] = "At least one uppercase letter required";
        }
        elseif (!preg_match('/[a-z]/', $password_raw)) {
            $errors['password'] = "At least one lowercase letter required";
        }
        elseif (!preg_match('/[0-9]/', $password_raw)) {
            $errors['password'] = "At least one number required";
        }
        elseif (!preg_match('/[@$!%*?&]/', $password_raw)) {
            $errors['password'] = "At least one special character required";
        }
        ?>