<?php

namespace App\Entity;

/**
 * Interface for recognizing soft deletable entity
 * It's also required to implement the entity table column `deleted`
 *
 * Interface SoftDeletableInterface
 * @package App\Entity
 */
interface SoftDeletableInterface {

    const FIELD_NAME_DELETED = "deleted";

    /**
     * @var bool
     */
    public function setDeleted(bool $deleted): void;

    /**
     * @return bool
     */
    public function isDeleted(): bool;

}