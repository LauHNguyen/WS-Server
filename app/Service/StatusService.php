<?php
namespace WorkSpace\Service;

use WorkSpace\Model\Status;
use Exception;

class StatusService
{
    private $statusModel;

    public function __construct(Status $statusModel)
    {
        $this->statusModel = $statusModel;
    }

    private function findStatus($statusName, $projectId)
    {
        return $this->statusModel->where('Status', $statusName)
                                ->where('IDProject', $projectId)
                                ->where('IsDeleted', 0)
                                ->first();
    }

    public function createStatus($data, $IDUser)
    {
        $requiredFields = [
            'IDProject' => 'Project ID is required',
            'Status' => 'Status is required',
        ];

        // Kiểm tra các field bắt buộc
        foreach ($requiredFields as $field => $message) {
            if (empty($data[$field])) {
                throw new Exception($message);
            }
        }

        // Kiểm tra xem status đã tồn tại trong project chưa
        $existingStatus = $this->findStatus($data['Status'], $data['IDProject']);
        if ($existingStatus) {
            throw new Exception('Status already exists in this project');
        }

        // Tạo status mới
        return Status::create([
            "IDProject" => $data["IDProject"],
            "Status" => $data["Status"],
            "StatusOrder" => $data["StatusOrder"] ?? 0,
            "IsDeleted" => $data["IsDeleted"] ?? 0
        ]);
    }
    public function getAllStatuses($projectId = null)
    {
        try {
            $query = $this->statusModel->where('IsDeleted', 0);
            
            if ($projectId) {
                $query->where('IDProject', $projectId);
            }
            
            return $query->orderBy('StatusOrder', 'ASC')
                        ->get();
        } catch (Exception $e) {
            throw new Exception("Error fetching statuses: " . $e->getMessage());
        }
    }
} 