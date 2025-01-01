<?php
class ElectricityCalculator {
    private float $voltage;
    private float $current;
    private float $rate;
    
    public function __construct(float $voltage, float $current, float $rate) {
        $this->voltage = $voltage;
        $this->current = $current;
        $this->rate = $rate;
    }
    
    public function calculatePower(): float {
        return $this->voltage * $this->current; // Power in Watts
    }
    
    public function calculateEnergy(float $hours): float {
        return ($this->calculatePower() * $hours) / 1000; // Energy in kWh
    }
    
    public function calculateTotal(float $hours): float {
        return $this->calculateEnergy($hours) * ($this->rate / 100);
    }
}

// Process form submission
$result = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voltage = filter_input(INPUT_POST, 'voltage', FILTER_VALIDATE_FLOAT);
    $current = filter_input(INPUT_POST, 'current', FILTER_VALIDATE_FLOAT);
    $rate = filter_input(INPUT_POST, 'rate', FILTER_VALIDATE_FLOAT);
    
    if ($voltage && $current && $rate) {
        $calculator = new ElectricityCalculator($voltage, $current, $rate);
        $result = [
            'power' => $calculator->calculatePower(),
            'energy_hour' => $calculator->calculateEnergy(1),
            'energy_day' => $calculator->calculateEnergy(24),
            'total_hour' => $calculator->calculateTotal(1),
            'total_day' => $calculator->calculateTotal(24)
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Consumption Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Electricity Consumption Calculator</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label for="voltage">Voltage (V)</label>
                                <input type="number" step="0.01" class="form-control" id="voltage" name="voltage" required>
                            </div>
                            <div class="form-group">
                                <label for="current">Current (A)</label>
                                <input type="number" step="0.01" class="form-control" id="current" name="current" required>
                            </div>
                            <div class="form-group">
                                <label for="rate">Rate (RM per kWh)</label>
                                <input type="number" step="0.01" class="form-control" id="rate" name="rate" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Calculate</button>
                        </form>