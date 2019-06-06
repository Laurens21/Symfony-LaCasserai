<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 */
class Payment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bank_details;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $card_details;

    /**
     * @ORM\Column(type="date")
     */
    private $date_payment_due;

    /**
     * @ORM\Column(type="date")
     */
    private $date_payment_made;

    /**
     * @ORM\Column(type="float")
     */
    private $amount_paid;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Paymentmethod")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentmethod_id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Booking", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBankDetails(): ?string
    {
        return $this->bank_details;
    }

    public function setBankDetails(string $bank_details): self
    {
        $this->bank_details = $bank_details;

        return $this;
    }

    public function getCardDetails(): ?string
    {
        return $this->card_details;
    }

    public function setCardDetails(string $card_details): self
    {
        $this->card_details = $card_details;

        return $this;
    }

    public function getDatePaymentDue(): ?\DateTimeInterface
    {
        return $this->date_payment_due;
    }

    public function setDatePaymentDue(\DateTimeInterface $date_payment_due): self
    {
        $this->date_payment_due = $date_payment_due;

        return $this;
    }

    public function getDatePaymentMade(): ?\DateTimeInterface
    {
        return $this->date_payment_made;
    }

    public function setDatePaymentMade(\DateTimeInterface $date_payment_made): self
    {
        $this->date_payment_made = $date_payment_made;

        return $this;
    }

    public function getAmountPaid(): ?float
    {
        return $this->amount_paid;
    }

    public function setAmountPaid(float $amount_paid): self
    {
        $this->amount_paid = $amount_paid;

        return $this;
    }

    public function getPaymentmethodId(): ?paymentmethod
    {
        return $this->paymentmethod_id;
    }

    public function setPaymentmethodId(?paymentmethod $paymentmethod_id): self
    {
        $this->paymentmethod_id = $paymentmethod_id;

        return $this;
    }

    public function getBookingId(): ?booking
    {
        return $this->booking_id;
    }

    public function setBookingId(booking $booking_id): self
    {
        $this->booking_id = $booking_id;

        return $this;
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
}
