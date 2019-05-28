<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceRepository")
 */
class Invoice
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
    private $invoiceNumber;

    /**
     * @ORM\Column(type="date")
     */
    private $invoiceDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $buyerName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $buyerAddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $buyerNip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sellerName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sellerAddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sellerNip;

    /**
     * @ORM\Column(type="float")
     */
    private $netValue;

    /**
     * @ORM\Column(type="float")
     */
    private $taxRate;

    /**
     * @ORM\Column(type="float")
     */
    private $taxValue;

    /**
     * @ORM\Column(type="float")
     */
    private $totalValue;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $unit;

    /**
     * @ORM\Column(type="float")
     */
    private $pricePerUnit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(string $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getInvoiceDate(): ?\DateTimeInterface
    {
        return $this->invoiceDate;
    }

    public function setInvoiceDate(\DateTimeInterface $invoiceDate): self
    {
        $this->invoiceDate = $invoiceDate;

        return $this;
    }

    public function getBuyerName(): ?string
    {
        return $this->buyerName;
    }

    public function setBuyerName(string $buyerName): self
    {
        $this->buyerName = $buyerName;

        return $this;
    }

    public function getBuyerAddress(): ?string
    {
        return $this->buyerAddress;
    }

    public function setBuyerAddress(string $buyerAddress): self
    {
        $this->buyerAddress = $buyerAddress;

        return $this;
    }

    public function getBuyerNip(): ?string
    {
        return $this->buyerNip;
    }

    public function setBuyerNip(string $buyerNip): self
    {
        $this->buyerNip = $buyerNip;

        return $this;
    }

    public function getSellerName(): ?string
    {
        return $this->sellerName;
    }

    public function setSellerName(string $sellerName): self
    {
        $this->sellerName = $sellerName;

        return $this;
    }

    public function getSellerAddress(): ?string
    {
        return $this->sellerAddress;
    }

    public function setSellerAddress(string $sellerAddress): self
    {
        $this->sellerAddress = $sellerAddress;

        return $this;
    }

    public function getSellerNip(): ?string
    {
        return $this->sellerNip;
    }

    public function setSellerNip(string $sellerNip): self
    {
        $this->sellerNip = $sellerNip;

        return $this;
    }

    public function getNetValue(): ?float
    {
        return $this->netValue;
    }

    public function setNetValue(float $netValue): self
    {
        $this->netValue = $netValue;

        return $this;
    }

    public function getTaxRate(): ?float
    {
        return $this->taxRate;
    }

    public function setTaxRate(float $taxRate): self
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    public function getTaxValue(): ?float
    {
        return $this->taxValue;
    }

    public function setTaxValue(float $taxValue): self
    {
        $this->taxValue = $taxValue;

        return $this;
    }

    public function getTotalValue(): ?float
    {
        return $this->totalValue;
    }

    public function setTotalValue(float $totalValue): self
    {
        $this->totalValue = $totalValue;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUnit(): ?int
    {
        return $this->unit;
    }

    public function setUnit(int $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getPricePerUnit(): ?float
    {
        return $this->pricePerUnit;
    }

    public function setPricePerUnit(float $pricePerUnit): self
    {
        $this->pricePerUnit = $pricePerUnit;

        return $this;
    }

    // public function __toString()
    // {
    //     return $this->getInvoiceNumber();
    // }
}
