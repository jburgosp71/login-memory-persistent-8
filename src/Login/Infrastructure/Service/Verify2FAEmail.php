<?php

namespace LoginMemoryPersistent\Infrastructure\Service;

use LoginMemoryPersistent\Domain\Entity\User;
use LoginMemoryPersistent\Domain\Exceptions\Validation2FAException;
use LoginMemoryPersistent\Domain\Service\Verify2FA\Verify2FAInterface;
use Random\RandomException;

class Verify2FAEmail implements Verify2FAInterface
{
    /*
     * Repositorio donde se guarda el código generado para cada usuario:
     *    se debería crear un Domain/repository y un Domain/Entity
     *    es sólo demostrativo, también le faltaría el expiration para que se borrara pasado X tiempo
     *    (sería sencillo crear una clave Redis con un expiration)
     */
    private array $codeStorage = [];

    /**
     * @throws Validation2FAException
     * @throws RandomException
     */
    public function verifyCode(User $user, string $code): bool
    {
        $username = $user->getUsername();

        if (!isset($this->codeStorage[$username])) {
            // Generar y enviar
            $generated = random_int(100000, 999999);
            $this->codeStorage[$username] = $generated;

            // Aquí vendría la lógica del envío de email
            echo "Sending email to {$username} with code: $generated\n";
            throw new Validation2FAException("2FA code sent via email");
        }

        if ($this->codeStorage[$username] !== (int)$code) {
            throw new Validation2FAException("Invalid 2FA code");
        }

        unset($this->codeStorage[$username]);
        return true;
    }
}