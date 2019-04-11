<?php
namespace iiko\Entity;

class OrderItem implements \JsonSerializable
{
    protected $id;
    protected $code;
    protected $name;
    protected $amount;
    protected $comment;
    protected $modifiers;

    public function __construct($name, $amount)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->validateAmount($amount);
    }

    public function validateAmount($amount)
    {
        if ($amount > 1000) {
            throw new \InvalidArgumentException("Invalid amount. More then 1000 is not allowed!. Input was {$amount}");
        }
    }

    public function jsonSerialize()
    {
        $props =  [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'amount' => $this->amount,
            'comment' => $this->comment,
            'modifiers' => $this->modifiers
        ];

        return array_filter($props);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getModifiers()
    {
        return $this->modifiers;
    }

    /**
     * @param mixed $modifiers
     */
    public function setModifiers($modifiers)
    {
        $this->modifiers = $modifiers;
    }
}