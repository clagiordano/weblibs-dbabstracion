<?php

namespace clagiordano\weblibs\dbabstraction;

/**
 * Response class for output/return formatted JSON data from array
 *
 * @author Claudio Giordano <claudio.giordano@autistici.org>
 */
class Response
{
    /**
     * @var array
     */
    private $DataArray = null;
    /**
     * @var array
     */
    private $ResponseData = null;
    /**
     * Create a Response object and set data
     *
     * @param array $data
     * @return \weblibs\php\Response
     */
    public function __construct(array $data = null)
    {
        $this->setResponse($data);
        return $this;
    }
    /**
     * Set response data
     *
     * @param array $data
     * @return \weblibs\php\Response
     */
    public function setResponse(array $data = null)
    {
        $this->DataArray    = $data;
        $this->ResponseData = json_encode($data);
        return $this;
    }
    /**
     * Print or return ResponseData
     *
     * @param boolean $printResponse
     * @return array|null
     */
    public function getResponse($printResponse = true)
    {
        $this->setStatusInfo();
        if ($printResponse) {
            header('Content-Type: application/json');
            echo $this->ResponseData;
        } else {
            return $this->ResponseData;
        }
        return null;
    }
    /**
     * Append status info to a response data
     *
     * @return \weblibs\php\Response
     */
    private function setStatusInfo()
    {
        $statusInfo['status'] = [
            'data' => date('Y-m-d H:i:s')
        ];
        $origData = json_decode($this->ResponseData, true);
        if (!is_null($origData)) {
            $this->ResponseData = json_encode(array_merge($origData, $statusInfo));
        } else {
            $this->ResponseData = json_encode($statusInfo);
        }
        return $this;
    }
}
