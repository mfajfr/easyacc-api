<?php
/**
 * @author Bc. Marek Fajfr <mfajfr90(at)gmail.com>
 * Created at: 9:29 21.11.2018
 */

namespace AccountancyAPI;

class AccountingRecord implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $reference_id = '';
    /**
     * @var string
     */
    protected $variable_number = '';
    /**
     * @var float
     */
    protected $price_without_tax;
    /**
     * @var float
     */
    protected $price_tax;
    /**
     * @var \DateTime
     */
    protected $taxed_at;
    /**
     * @var \DateTime
     */
    protected $dued_at;
    /**
     * @var \DateTime|null
     */
    protected $paid_at = null;

    /**
     * AccountingRecord constructor.
     * @param string $reference_id
     * @param string $variable_number
     * @param $price_without_tax
     * @param $price_tax
     * @param $taxed_at
     * @param $dued_at
     * @param null $paid_at
     */
    public function __construct($reference_id = '', $variable_number = '', $price_without_tax, $price_tax, \DateTime $taxed_at, \DateTime $dued_at, \DateTime $paid_at = null)
    {
        $this->reference_id = $reference_id;
        $this->variable_number = $variable_number;
        $this->price_without_tax = $price_without_tax;
        $this->price_tax = $price_tax;
        $this->taxed_at = $taxed_at;
        $this->dued_at = $dued_at;
        $this->paid_at = $paid_at;
    }

    public function store(Connection $connection)
    {
        return $connection->response($connection->post('accounting-record/store', $this->jsonSerialize()));
    }

    public static function pay(Connection $connection, $accounting_record_uid, \DateTime $paid_at)
    {
        return $connection->response($connection->patch('accounting-record/uid/' . $accounting_record_uid . '/pay', [
            'paid_at' => $paid_at->format('Y-m-d H:i:s')
        ]));
    }

    public static function delete(Connection $connection, $accounting_record_uid)
    {
        return $connection->response($connection->delete('accounting-record/uid/' . $accounting_record_uid));
    }

    public static function invoice(Connection $connection, $accounting_record_uid, $invoice)
    {
        return $connection->response($connection->data('accounting-record/uid/' . $accounting_record_uid . '/invoice', $invoice));
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'reference_id'      => $this->reference_id,
            'variable_number'   => $this->variable_number,
            'price_without_tax' => $this->price_without_tax,
            'price_tax'         => $this->price_tax,
            'taxed_at'          => $this->taxed_at->format('Y-m-d H:i:s'),
            'dued_at'           => $this->dued_at->format('Y-m-d H:i:s'),
            'paid_at'           => is_null($this->paid_at) ? null : $this->paid_at->format('Y-m-d H:i:s')
        ];
    }
}