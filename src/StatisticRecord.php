<?php
/**
 * @author Bc. Marek Fajfr <mfajfr90(at)gmail.com>
 * Created at: 10:52 21.11.2018
 */

namespace AccountancyAPI;

class StatisticRecord
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
     * @var int
     */
    protected $quantity;
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
     * @param $price_without_tax
     * @param $created_at
     */
    public function __construct($reference_id = '', $price_without_tax, $quantity, \DateTime $created_at)
    {
        $this->reference_id = $reference_id;
        $this->price_without_tax = $price_without_tax;
        $this->quantity = $quantity;
        $this->created_at = $created_at;
    }

    public function store(Connection $connection)
    {
        return $connection->response($connection->post('statistic-record/store', $this->jsonSerialize()));
    }

    public static function delete(Connection $connection, $statistic_record_uid)
    {
        return $connection->response($connection->delete('statistic-record/uid/' . $statistic_record_uid . '/pay'));
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
            'price_without_tax' => $this->price_without_tax,
            'quantity'          => $this->quantity,
            'created_at'        => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}