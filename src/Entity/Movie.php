<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    #[ORM\Column]
    private ?int $ReleaseYear = null;

    #[ORM\Column(length: 255)]
    private ?string $ShortDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Image = null;

    #[ORM\Column(nullable: true)]
    private ?int $Rating = null;

    #[ORM\ManyToMany(targetEntity: Actor::class, inversedBy: 'movies')]
    private Collection $Actors;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: ReviewNRating::class)]
    private Collection $reviewNRatings;

    #[ORM\Column(length: 255)]
    private ?string $director = null;

    #[ORM\Column]
    private ?int $runTime = null;


    public function __construct()
    {
        $this->Actors = new ArrayCollection();
        $this->reviewNRatings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    public function getReleaseYear(): ?int
    {
        return $this->ReleaseYear;
    }

    public function setReleaseYear(int $ReleaseYear): static
    {
        $this->ReleaseYear = $ReleaseYear;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->ShortDescription;
    }

    public function setShortDescription(string $ShortDescription): static
    {
        $this->ShortDescription = $ShortDescription;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): static
    {
        $this->Image = $Image;

        return $this;
    }


    /**
     * @return Collection<int, Actor>
     */
    public function getActors(): Collection
    {
        return $this->Actors;
    }

    public function addActor(Actor $actor): static
    {
        if (!$this->Actors->contains($actor)) {
            $this->Actors->add($actor);
        }

        return $this;
    }

    public function removeActor(Actor $actor): static
    {
        $this->Actors->removeElement($actor);

        return $this;
    }

    /**
     * @return Collection<int, ReviewNRating>
     */
    public function getReviewNRatings(): Collection
    {
        return $this->reviewNRatings;
    }

    public function addReviewNRating(ReviewNRating $reviewNRating): static
    {
        if (!$this->reviewNRatings->contains($reviewNRating)) {
            $this->reviewNRatings->add($reviewNRating);
            $reviewNRating->setMovie($this);
        }

        return $this;
    }

    public function removeReviewNRating(ReviewNRating $reviewNRating): static
    {
        if ($this->reviewNRatings->removeElement($reviewNRating)) {
            // set the owning side to null (unless already changed)
            if ($reviewNRating->getMovie() === $this) {
                $reviewNRating->setMovie(null);
            }
        }

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): static
    {
        $this->director = $director;

        return $this;
    }

    public function getRunTime(): ?int
    {
        return $this->runTime;
    }

    public function setRunTime(int $runTime): static
    {
        $this->runTime = $runTime;

        return $this;
    }

}
