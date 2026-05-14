<?php

namespace App\Controllers;

class ClientPortal extends BaseController
{
    public function __construct(){
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        $this->session = session();
        $this->cuser = $this->session->get('__xsys_myuserzicas__');
    }

    public function index(){
        if (!session('__xsys_myuserzicas_is_logged__')) {
            return view('MyLogin');
        }
        
        // Get current user info
        $query = $this->db->query("
            SELECT 'TEST NAME KYLE ANDRAE' AS `full_name`, 'PROGRAMMER' AS `position`, `username`
            FROM `myua_user` 
            WHERE `username` = '{$this->cuser}'"
        );
        $userData = $query->getRowArray();
        
        // Check if tables exist
        $tablesExist = $this->checkTablesExist();
        
        if (!$tablesExist) {
            $portalData = $this->getMockupData();
        } else {
            $portalData = $this->getRealData();
        }
        
        $portalData['full_name'] = $userData['full_name'] ?? 'Admin';
        $portalData['position'] = $userData['position'] ?? 'Client Portal Manager';
        
        return view('ClientPortal', $portalData);
    }
    
    private function checkTablesExist()
    {
        try {
            $result = $this->db->query("SHOW TABLES LIKE 'erp_shipments'");
            return $result->getNumRows() > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function getMockupData()
    {
        return [
            'company_name' => 'TransGlobal Logistics Ltd.',
            'client_name' => 'Michael Chen',
            'freight_spend' => '4.28M',
            'at_risk_count' => 3,
            'otif_score' => 94.2,
            'carbon_offset' => '184t',
            'avg_otif' => 94,
            'avg_on_time' => 96,
            'avg_in_full' => 98,
            'root_cause' => 'Port Congestion',
            'shipments' => [
                ['tracking_id' => 'LGA-4219', 'origin' => 'Shanghai', 'destination' => 'Hamburg', 'status' => 'in_transit', 'estimated_delivery_date' => 'Apr 14-16', 'risk_score' => 'HIGH'],
                ['tracking_id' => 'LGA-8472', 'origin' => 'Mumbai', 'destination' => 'Rotterdam', 'status' => 'delivered', 'estimated_delivery_date' => 'Delivered Apr 08', 'risk_score' => 'LOW'],
                ['tracking_id' => 'LGA-0023', 'origin' => 'Alexandria', 'destination' => 'Valencia', 'status' => 'in_transit', 'estimated_delivery_date' => 'Apr 15-16', 'risk_score' => 'MEDIUM'],
                ['tracking_id' => 'LGA-3391', 'origin' => 'Gothenburg', 'destination' => 'Chicago', 'status' => 'pending', 'estimated_delivery_date' => 'Pending', 'risk_score' => 'HIGH'],
                ['tracking_id' => 'LGA-9074', 'origin' => 'Lima', 'destination' => 'Miami', 'status' => 'delivered', 'estimated_delivery_date' => 'Delivered Apr 09', 'risk_score' => 'LOW']
            ],
            'alerts' => [
                ['tracking_id' => 'LGA-4219', 'alert_message' => 'Port congestion detected', 'risk_score' => 'HIGH', 'estimated_delivery_date' => '2026-04-16'],
                ['tracking_id' => 'LGA-0023', 'alert_message' => 'Weather delay in Mediterranean', 'risk_score' => 'MEDIUM', 'estimated_delivery_date' => '2026-04-15'],
                ['tracking_id' => 'LGA-3391', 'alert_message' => 'Customs documentation required', 'risk_score' => 'HIGH', 'estimated_delivery_date' => '2026-04-14']
            ],
            'active_shipment' => [
                'tracking_id' => 'LGA-4219',
                'origin' => 'Shanghai',
                'destination' => 'Hamburg',
                'status' => 'in_transit',
                'estimated_delivery_date' => '2026-04-16'
            ],
            'journey' => [
                ['location' => 'Shanghai Port', 'date' => 'Apr 01, 2026', 'completed' => true, 'current' => false],
                ['location' => 'South China Sea', 'date' => 'Apr 03, 2026', 'completed' => true, 'current' => false],
                ['location' => 'Strait of Malacca', 'date' => 'Apr 06, 2026', 'completed' => true, 'current' => false],
                ['location' => 'Indian Ocean', 'date' => 'Apr 09, 2026', 'completed' => false, 'current' => true],
                ['location' => 'Colombo', 'date' => 'Expected Apr 14', 'completed' => false, 'current' => false],
                ['location' => 'Hamburg Port', 'date' => 'Expected Apr 22', 'completed' => false, 'current' => false]
            ]
        ];
    }
    
    private function getRealData()
    {
        // Get client ID from session or use first client for demo
        $clientId = $this->db->query("SELECT client_id FROM b2b_clients WHERE status = 'active' LIMIT 1")->getRow()->client_id ?? 1;
        
        // Get company info
        $company = $this->db->query("SELECT company_name FROM b2b_clients WHERE client_id = {$clientId}")->getRow();
        
        // Get KPIs
        $kpiResult = $this->db->query("
            SELECT 
                (SELECT COALESCE(SUM(total_amount), 0) FROM b2b_invoices WHERE client_id = {$clientId} AND MONTH(invoice_date) = MONTH(CURDATE())) as freight_spend,
                (SELECT COUNT(*) FROM erp_shipments WHERE client_id = {$clientId} AND status = 'at_risk') as at_risk_count,
                (SELECT COALESCE(AVG(otif_score), 94) FROM erp_shipments WHERE client_id = {$clientId}) as otif_score
        ")->getRow();
        
        // Get OTIF
        $otifResult = $this->db->query("
            SELECT 
                COALESCE(AVG(otif_score), 94) as avg_otif,
                COALESCE(AVG(CASE WHEN is_on_time = 1 THEN 100 ELSE 0 END), 96) as on_time,
                COALESCE(AVG(CASE WHEN is_in_full = 1 THEN 100 ELSE 0 END), 98) as in_full
            FROM erp_shipments
            WHERE client_id = {$clientId}
        ")->getRow();
        
        // Get shipments
        $shipments = $this->db->query("
            SELECT 
                tracking_id,
                origin_city as origin,
                destination_city as destination,
                status,
                DATE_FORMAT(estimated_delivery_date, '%b %d') as estimated_delivery_date,
                risk_score
            FROM erp_shipments
            WHERE client_id = {$clientId}
            ORDER BY created_at DESC
            LIMIT 10
        ")->getResultArray();
        
        // Get alerts
        $alerts = $this->db->query("
            SELECT 
                tracking_id,
                risk_reason as alert_message,
                risk_score,
                estimated_delivery_date
            FROM erp_shipments
            WHERE client_id = {$clientId} AND risk_score IN ('HIGH', 'MEDIUM') AND status != 'delivered'
            LIMIT 5
        ")->getResultArray();
        
        return [
            'company_name' => $company->company_name ?? 'Client Company',
            'client_name' => 'Client User',
            'freight_spend' => number_format(($kpiResult->freight_spend ?? 0) / 1000000, 2) . 'M',
            'at_risk_count' => $kpiResult->at_risk_count ?? 0,
            'otif_score' => round($kpiResult->otif_score ?? 94.2, 1),
            'carbon_offset' => '184t',
            'avg_otif' => round($otifResult->avg_otif ?? 94),
            'avg_on_time' => round($otifResult->on_time ?? 96),
            'avg_in_full' => round($otifResult->in_full ?? 98),
            'root_cause' => 'Port Congestion',
            'shipments' => !empty($shipments) ? $shipments : $this->getMockupData()['shipments'],
            'alerts' => !empty($alerts) ? $alerts : $this->getMockupData()['alerts'],
            'active_shipment' => !empty($shipments[0]) ? $shipments[0] : $this->getMockupData()['active_shipment'],
            'journey' => $this->getMockupData()['journey']
        ];
    }
}