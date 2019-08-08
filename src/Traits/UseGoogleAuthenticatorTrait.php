<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait UseGoogleAuthenticatorTrait
{
    /**
     * @var string|null
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $mfaKey;

    public function getMfaKey(): ?string
    {
        return $this->mfaKey;
    }

    public function setMfaKey(?string $mfaKey): self
    {
        $this->mfaKey = $mfaKey;

        return $this;
    }

    public function isMfaEnabled(): bool
    {
        return null !== $this->mfaKey;
    }
}
