<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $nom;

    /**
     * @Assert\GreaterThanOrEqual("today UTC", message="La date doit être supérieur a la date d'aujourd'hui !")
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @ORM\Column(type="integer")
     */
    private $duree;

    /**
     * @Assert\LessThanOrEqual(propertyPath="dateHeureDebut", message="La date limite d'inscription doit être inférieur a la date de début de sortie !")
     * @ORM\Column(type="datetime")
     *
     */
    private $dateLimiteInscription;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbInscriptionsMax;

    /**
     * @ORM\Column(type="text")
     */
    private $infoSortie;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, mappedBy="sortie")
     */
    private $idparticipant;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="sortie")
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="organisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descriptionAnnul;


    public function __construct()
    {
        $this->idparticipant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getInfoSortie(): ?string
    {
        return $this->infoSortie;
    }

    public function setInfoSortie(string $infoSortie): self
    {
        $this->infoSortie = $infoSortie;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getIdparticipant(): Collection
    {
        return $this->idparticipant;
    }

    public function addIdparticipant(Participant $idparticipant): self
    {
        if (!$this->idparticipant->contains($idparticipant)) {
            $this->idparticipant[] = $idparticipant;
            $idparticipant->addSortie($this);
        }

        return $this;
    }

    public function removeIdparticipant(Participant $idparticipant): self
    {
        if ($this->idparticipant->removeElement($idparticipant)) {
            $idparticipant->removeSortie($this);
        }

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participant $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDescriptionAnnul(): ?string
    {
        return $this->descriptionAnnul;
    }

    public function setDescriptionAnnul(?string $descriptionAnnul): self
    {
        $this->descriptionAnnul = $descriptionAnnul;

        return $this;
    }
}
