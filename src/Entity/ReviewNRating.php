<?php

namespace App\Entity;

use App\Repository\ReviewNRatingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReviewNRatingRepository::class)]
class ReviewNRating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['movie_review_details'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reviewNRatings')]
    #[Groups(['movie_review_details'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reviewNRatings')]
    #[Groups(['movie_review_details'])]
    private ?Movie $movie = null;

    #[ORM\Column]
    #[Groups(['movie_review_details'])]
    private ?int $rating = null;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Groups(['movie_review_details'])]
    private ?string $review = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getMovie(): ?movie
    {
        return $this->movie;
    }

    public function setMovie(?movie $movie): static
    {
        $this->movie = $movie;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(?string $review): static
    {
        $this->review = $review;

        return $this;
    }
}
