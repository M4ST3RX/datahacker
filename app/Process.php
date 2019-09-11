<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Process extends Model
{
    protected $primaryKey = 'process_id';

    protected $computer_id;
    protected $related_ip;
    protected $type;
    protected $data;
    protected $start;
    protected $end;
    protected $is_cpu;

    public function setData($id, $ip, $type, $data, $second, $is_cpu){
        $startDate = Carbon::now()->format("Y-m-d H:i:s");
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate)->addSeconds($second);
        $this->computer_id = $id;
        $this->related_ip = $ip;
        $this->type = $type;
        $this->data = $data;
        $this->start = $startDate;
        $this->is_cpu = $is_cpu;
        $this->end = $endDate;
    }

    public function saveProcess(){
        return Process::insertGetId([
            'computer_id' => $this->computer_id,
            'related_ip' => $this->related_ip,
            'type' => $this->type,
            'data' => $this->data,
            'start_time' => $this->start,
            'end_time' => $this->end,
            'is_cpu' => $this->is_cpu,
        ]);
    }
}
