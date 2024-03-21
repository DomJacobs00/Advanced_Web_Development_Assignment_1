<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;




#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['movie_details','movie_review_details'])]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie_details','movie_review_details'])]
    private ?string $Title = null;

    #[ORM\Column]
    #[Groups(['movie_details','movie_review_details'])]
    private ?int $ReleaseYear = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie_details'])]
    private ?string $ShortDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['movie_details'])]
    private ?string $Image = null;


    #[ORM\ManyToMany(targetEntity: Actor::class, inversedBy: 'movies', cascade: ['persist'])]
    #[Groups(['movie_details'])]
    private Collection $Actors;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: ReviewNRating::class)]
    private Collection $reviewNRatings;

    #[ORM\Column]
    #[Groups(['movie_details'])]
    private ?int $runTime = null;

    #[ORM\ManyToMany(targetEntity: Director::class, inversedBy: 'movies', cascade: ['persist'])]
    #[Groups(['movie_details'])]
    private Collection $directors;


    public function __construct()
    {
        $this->Actors = new ArrayCollection();
        $this->reviewNRatings = new ArrayCollection();
        $this->directors = new ArrayCollection();
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



    public function getRunTime(): ?int
    {
        return $this->runTime;
    }

    public function setRunTime(int $runTime): static
    {
        $this->runTime = $runTime;

        return $this;
    }

    /**
     * @return Collection<int, Director>
     */
    public function getDirectors(): Collection
    {
        return $this->directors;
    }

    public function addDirector(Director $director): static
    {
        if (!$this->directors->contains($director)) {
            $this->directors->add($director);
        }

        return $this;
    }

    public function removeDirector(Director $director): static
    {
        $this->directors->removeElement($director);

        return $this;
    }

}
