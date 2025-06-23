<?php
$user = getCurrentUser();

// Generate sample analytics data
$analytics_data = [
    'total_attempts' => rand(1000, 5000),
    'average_score' => rand(65, 85),
    'completion_rate' => rand(75, 95),
    'popular_subjects' => [
        'Mathematics' => rand(200, 500),
        'Science' => rand(150, 400),
        'English' => rand(100, 300),
        'History' => rand(80, 250),
        'Geography' => rand(60, 200)
    ],
    'difficulty_distribution' => [
        'easy' => rand(30, 40),
        'medium' => rand(40, 50),
        'hard' => rand(20, 30)
    ],
    'monthly_trends' => [
        'Jan' => rand(50, 100),
        'Feb' => rand(60, 110),
        'Mar' => rand(70, 120),
        'Apr' => rand(80, 130),
        'May' => rand(90, 140),
        'Jun' => rand(100, 150)
    ]
];
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-gradient mb-0">Analytics & Insights</h1>
                <p class="text-muted">R&D Analytics, Pattern Recognition, and Performance Metrics</p>
            </div>
            <div>
                <button class="btn btn-success me-2">
                    <i class="fas fa-brain me-2"></i>AI Predictions
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-download me-2"></i>Export Report
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Key Metrics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= number_format($analytics_data['total_attempts']) ?></div>
                    <div class="stats-label">Total Attempts</div>
                </div>
                <i class="fas fa-chart-line fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= $analytics_data['average_score'] ?>%</div>
                    <div class="stats-label">Average Score</div>
                </div>
                <i class="fas fa-trophy fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?= $analytics_data['completion_rate'] ?>%</div>
                    <div class="stats-label">Completion Rate</div>
                </div>
                <i class="fas fa-check-circle fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">🔥 85</div>
                    <div class="stats-label">Trending Score</div>
                </div>
                <i class="fas fa-fire fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- R&D Analytics Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-brain me-2"></i>R&D Pattern Recognition
                    </h5>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary active">Question Patterns</button>
                        <button class="btn btn-sm btn-outline-primary">Topic Trends</button>
                        <button class="btn btn-sm btn-outline-primary">Predictions</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">🔄 Repeated Questions Analysis</h6>
                        <div class="bg-light p-3 rounded mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Linear Algebra Questions</span>
                                <div>
                                    <span class="badge bg-success me-2">High Repeat</span>
                                    <strong>73%</strong>
                                </div>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-success" style="width: 73%"></div>
                            </div>
                            <small class="text-muted">Appeared in: 2019, 2021, 2023 TNPSC</small>
                        </div>
                        
                        <div class="bg-light p-3 rounded mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Indian Constitution</span>
                                <div>
                                    <span class="badge bg-warning me-2">Medium Repeat</span>
                                    <strong>45%</strong>
                                </div>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-warning" style="width: 45%"></div>
                            </div>
                            <small class="text-muted">Appeared in: 2020, 2022 UPSC</small>
                        </div>
                        
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Organic Chemistry</span>
                                <div>
                                    <span class="badge bg-danger me-2">Low Repeat</span>
                                    <strong>18%</strong>
                                </div>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-danger" style="width: 18%"></div>
                            </div>
                            <small class="text-muted">Appeared in: 2023 State PSC</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">🎯 AI Predictions for 2024</h6>
                        <div class="prediction-item mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="prediction-icon me-3">
                                    <i class="fas fa-arrow-up text-success fa-lg"></i>
                                </div>
                                <div>
                                    <strong>High Probability (85%)</strong>
                                    <br><small class="text-muted">Data Structures & Algorithms</small>
                                </div>
                            </div>
                            <p class="small text-muted mb-0">Based on 3-year pattern analysis and curriculum changes</p>
                        </div>
                        
                        <div class="prediction-item mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="prediction-icon me-3">
                                    <i class="fas fa-arrow-right text-warning fa-lg"></i>
                                </div>
                                <div>
                                    <strong>Medium Probability (62%)</strong>
                                    <br><small class="text-muted">Environmental Science</small>
                                </div>
                            </div>
                            <p class="small text-muted mb-0">Emerging trend in recent exam patterns</p>
                        </div>
                        
                        <div class="prediction-item">
                            <div class="d-flex align-items-center mb-2">
                                <div class="prediction-icon me-3">
                                    <i class="fas fa-arrow-down text-danger fa-lg"></i>
                                </div>
                                <div>
                                    <strong>Low Probability (23%)</strong>
                                    <br><small class="text-muted">Classical Literature</small>
                                </div>
                            </div>
                            <p class="small text-muted mb-0">Declining trend over past 5 years</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Subject Performance Distribution
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="subjectChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Monthly Test Trends
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Analytics -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-microscope me-2"></i>Question Bank Analysis
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Topic</th>
                                <th>Questions</th>
                                <th>Difficulty Distribution</th>
                                <th>Success Rate</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Mathematics</strong>
                                    <br><small class="text-muted">Algebra, Geometry</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">342</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <span class="badge bg-success">E: 120</span>
                                        <span class="badge bg-warning">M: 150</span>
                                        <span class="badge bg-danger">H: 72</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: 78%">78%</div>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-arrow-up text-success"></i>
                                    <small class="text-success">+12%</small>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <strong>Science</strong>
                                    <br><small class="text-muted">Physics, Chemistry</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">285</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <span class="badge bg-success">E: 95</span>
                                        <span class="badge bg-warning">M: 120</span>
                                        <span class="badge bg-danger">H: 70</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-warning" style="width: 65%">65%</div>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-arrow-right text-warning"></i>
                                    <small class="text-muted">±0%</small>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <strong>History</strong>
                                    <br><small class="text-muted">Ancient, Modern</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">198</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <span class="badge bg-success">E: 80</span>
                                        <span class="badge bg-warning">M: 85</span>
                                        <span class="badge bg-danger">H: 33</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: 82%">82%</div>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-arrow-up text-success"></i>
                                    <small class="text-success">+8%</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Smart Insights
                </h5>
            </div>
            <div class="card-body">
                <div class="insight-item mb-3 p-3 bg-success bg-opacity-10 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-chart-line text-success me-2"></i>
                        <strong class="text-success">Performance Boost</strong>
                    </div>
                    <p class="small mb-0">Mathematics scores improved by 15% after adding more practice questions.</p>
                </div>
                
                <div class="insight-item mb-3 p-3 bg-warning bg-opacity-10 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        <strong class="text-warning">Attention Needed</strong>
                    </div>
                    <p class="small mb-0">Science topics show declining engagement. Consider adding visual content.</p>
                </div>
                
                <div class="insight-item mb-3 p-3 bg-info bg-opacity-10 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-brain text-info me-2"></i>
                        <strong class="text-info">AI Recommendation</strong>
                    </div>
                    <p class="small mb-0">Focus on Environment & Ecology questions for upcoming exams.</p>
                </div>
                
                <div class="insight-item p-3 bg-primary bg-opacity-10 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-trophy text-primary me-2"></i>
                        <strong class="text-primary">Best Performer</strong>
                    </div>
                    <p class="small mb-0">History questions have the highest completion rate at 89%.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pattern Recognition Heatmap -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-fire me-2"></i>Question Repetition Heatmap
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-2 mb-3">
                        <div class="heatmap-cell bg-danger bg-opacity-75 p-3 rounded">
                            <strong>Linear Equations</strong>
                            <br><small>90% Repeat Rate</small>
                            <br><span class="badge bg-white text-dark">High Priority</span>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="heatmap-cell bg-warning bg-opacity-75 p-3 rounded">
                            <strong>Indian Constitution</strong>
                            <br><small>65% Repeat Rate</small>
                            <br><span class="badge bg-white text-dark">Medium Priority</span>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="heatmap-cell bg-success bg-opacity-75 p-3 rounded">
                            <strong>Geography</strong>
                            <br><small>45% Repeat Rate</small>
                            <br><span class="badge bg-white text-dark">Normal</span>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="heatmap-cell bg-info bg-opacity-75 p-3 rounded">
                            <strong>Literature</strong>
                            <br><small>28% Repeat Rate</small>
                            <br><span class="badge bg-white text-dark">Low Priority</span>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="heatmap-cell bg-secondary bg-opacity-75 p-3 rounded">
                            <strong>Art & Culture</strong>
                            <br><small>15% Repeat Rate</small>
                            <br><span class="badge bg-white text-dark">Rare</span>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="heatmap-cell bg-primary bg-opacity-75 p-3 rounded">
                            <strong>Current Affairs</strong>
                            <br><small>85% Repeat Rate</small>
                            <br><span class="badge bg-white text-dark">Critical</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>