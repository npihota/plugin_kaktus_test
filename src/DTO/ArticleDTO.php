<?php

declare(strict_types=1);


namespace App\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class ArticleDTO
{
    /**
     * @Assert\NotBlank()
     */
    public $title;

    /**
     * @Assert\Collection()
     */
    public $authors;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    public $createdAt;

}