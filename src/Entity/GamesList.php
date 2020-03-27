<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GamesListRepository")
 * @UniqueEntity("name")
 */
class GamesList
{
    const CATEG = array(
        1 => 'Extérieur',
        2 => 'Intérieur',
        3 => 'Tous Terrains'
    );
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Regex("/^[\w\s\.\:\,\?\!\'\«\»""\-\+\=\$\€\%\&\*éèêëûüùîïíôöœàáâæ]+$/",
     *     message="Vous utilisez des caractères interdits")
     * @ORM\Column(type="string", length=80, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $materiel;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $categories;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $validate;

    /**
     * @ORM\Column(type="text")
     */
    private $rules_description;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rules_win;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rules_url;

    /**
     * @ORM\Column(type="text")
     */
    private $rules_details;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMateriel(): ?bool
    {
        return $this->materiel;
    }

    public function setMateriel(bool $materiel): self
    {
        $this->materiel = $materiel;

        return $this;
    }

    public function formatMateriel()
    {
        return $this->materiel ? 'avec' : 'sans';
    }


    public function getCategories(): ?int
    {
        return $this->categories;
    }

    public function getCategType(): string
    {
        return self::CATEG[$this->categories];
    }

    public function setCategories(?int $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getValidate(): ?bool
    {
        return $this->validate;
    }

    public function getValidateStr(): string
    {
        return ($this->validate == 1) ? 'Oui' : 'Non';
    }

    public function setValidate(?bool $validate): self
    {
        $this->validate = $validate;

        return $this;
    }

    public function getRulesDescription(): ?string
    {
        return $this->rules_description;
    }

    public function setRulesDescription(string $rules_description): self
    {
        $this->rules_description = $rules_description;

        return $this;
    }


    public function getRulesWin(): ?string
    {
        return $this->rules_win;
    }

    public function setRulesWin(string $rules_win): self
    {
        $this->rules_win = $rules_win;

        return $this;
    }

    public function getRulesUrl(): ?string
    {
        return $this->rules_url;
    }

    public function setRulesUrl(?string $rules_url): self
    {
        $this->rules_url = $rules_url;

        return $this;
    }

    public function getRulesDetails(): ?string
    {
        return $this->rules_details;
    }

    public function setRulesDetails(string $rules_details): self
    {
        $this->rules_details = $rules_details;

        return $this;
    }
}
