<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExamPro - Advanced Test Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#4F46E5',
                    secondary: '#10B981',
                    dark: '#1F2937',
                    light: '#F9FAFB',
                    danger: '#EF4444',
                    warning: '#F59E0B',
                    info: '#3B82F6'
                }
            }
        }
    }
    </script>
    <style>
    .sidebar {
        transition: all 0.3s ease;
    }

    .sidebar.collapsed {
        width: 70px;
    }

    .sidebar.collapsed .sidebar-text {
        display: none;
    }

    .sidebar.collapsed .sidebar-icon {
        margin-right: 0;
    }

    .main-content {
        transition: all 0.3s ease;
    }

    .sidebar.collapsed+.main-content {
        margin-left: 70px;
    }

    .question-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .chart-container {
        height: 300px;
    }

    .proctor-view {
        background-color: #1F2937;
    }

    .test-timer {
        font-family: 'Courier New', monospace;
    }

    .drag-area {
        border: 2px dashed #D1D5DB;
    }

    .drag-area.active {
        border-color: #4F46E5;
        background-color: #EEF2FF;
    }

    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            z-index: 50;
            transform: translateX(-100%);
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .sidebar.collapsed+.main-content {
            margin-left: 0;
        }
    }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Mobile Menu Button -->
    <div class="md:hidden fixed top-4 left-4 z-50">
        <button id="mobileMenuButton" class="p-2 rounded-md bg-white shadow-md">
            <i class="fas fa-bars text-gray-700"></i>
        </button>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed top-0 left-0 h-screen w-64 bg-white shadow-lg overflow-y-auto">
        <div class="p-4 flex items-center justify-between border-b">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">EP
                </div>
                <span class="sidebar-text ml-3 text-xl font-semibold">ExamPro</span>
            </div>
            <button id="toggleSidebar" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-chevron-left sidebar-icon"></i>
            </button>
        </div>
        <div class="p-4">
            <div class="mb-6">
                <div class="text-xs uppercase font-semibold text-gray-500 mb-2 sidebar-text">Main Menu</div>
                <ul>
                    <li class="mb-1">
                        <a href="#" class="flex items-center p-2 rounded-lg bg-primary text-white">
                            <i class="fas fa-tachometer-alt sidebar-icon mr-3"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="#" class="flex items-center p-2 rounded-lg text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-book sidebar-icon mr-3"></i>
                            <span class="sidebar-text">Test Packs</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="#" class="flex items-center p-2 rounded-lg text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-clock sidebar-icon mr-3"></i>
                            <span class="sidebar-text">Mock Tests</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="#" class="flex items-center p-2 rounded-lg text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-shield sidebar-icon mr-3"></i>
                            <span class="sidebar-text">Proctored Tests</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="#" class="flex items-center p-2 rounded-lg text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-bolt sidebar-icon mr-3"></i>
                            <span class="sidebar-text">Instant Tests</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="#" class="flex items-center p-2 rounded-lg text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-database sidebar-icon mr-3"></i>
                            <span class="sidebar-text">Question Bank</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="#" class="flex items-center p-2 rounded-lg text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-chart-line sidebar-icon mr-3"></i>
                            <span class="sidebar-text">R&D Analytics</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="mb-6">
                <div class="text-xs uppercase font-semibold text-gray-500 mb-2 sidebar-text">Admin Tools</div>
                <ul>
                    <li class="mb-1">
                        <a href="#" class="flex items-center p-2 rounded-lg text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-users sidebar-icon mr-3"></i>
                            <span class="sidebar-text">User Management</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="#" class="flex items-center p-2 rounded-lg text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-building sidebar-icon mr-3"></i>
                            <span class="sidebar-text">Vendor Portal</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="#" class="flex items-center p-2 rounded-lg text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog sidebar-icon mr-3"></i>
                            <span class="sidebar-text">Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content ml-64 min-h-screen">
        <!-- Top Navigation -->
        <nav class="bg-white shadow-sm p-4 flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-xl font-semibold text-gray-800">Test Management Dashboard</h1>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <button class="p-2 rounded-full hover:bg-gray-100">
                        <i class="fas fa-bell text-gray-600"></i>
                        <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-danger"></span>
                    </button>
                </div>
                <div class="relative">
                    <button class="p-2 rounded-full hover:bg-gray-100">
                        <i class="fas fa-envelope text-gray-600"></i>
                        <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-primary"></span>
                    </button>
                </div>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                        AD</div>
                    <span class="ml-2 text-sm font-medium">Admin User</span>
                </div>
            </div>
        </nav>

        <!-- Dashboard Content -->
        <div class="p-6">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <i class="fas fa-book text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Test Packs</p>
                        <h3 class="text-2xl font-bold">142</h3>
                        <p class="text-green-500 text-sm flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> 12% from last month
                        </p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Active Students</p>
                        <h3 class="text-2xl font-bold">3,842</h3>
                        <p class="text-green-500 text-sm flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> 8% from last month
                        </p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <i class="fas fa-bolt text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Instant Tests</p>
                        <h3 class="text-2xl font-bold">327</h3>
                        <p class="text-green-500 text-sm flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> 23% from last month
                        </p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6 flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">R&D Questions</p>
                        <h3 class="text-2xl font-bold">12,459</h3>
                        <p class="text-green-500 text-sm flex items-center">
                            <i class="fas fa-arrow-up mr-1"></i> 5% from last month
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Dashboard Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Test Pack Creation -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-primary text-white p-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold">Create New Test Pack</h2>
                        <button class="text-white hover:text-gray-200">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <form>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-medium mb-2" for="title">Test Pack
                                    Title</label>
                                <input type="text" id="title"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Cover Image</label>
                                <div
                                    class="drag-area border-2 border-dashed border-gray-300 rounded-md p-4 text-center cursor-pointer hover:border-primary transition">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-500">Drag & drop your image here or click to browse</p>
                                    <input type="file" class="hidden">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2" for="mrp">MRP</label>
                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₹</span>
                                        <input type="number" id="mrp"
                                            class="w-full pl-8 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2" for="price">Selling
                                        Price</label>
                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">₹</span>
                                        <input type="number" id="price"
                                            class="w-full pl-8 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Question Bank
                                    Selection</label>
                                <div class="border border-gray-300 rounded-md p-2">
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded flex items-center">
                                            Mathematics - Algebra <button
                                                class="ml-1 text-blue-600 hover:text-blue-800"><i
                                                    class="fas fa-times"></i></button>
                                        </span>
                                        <span
                                            class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded flex items-center">
                                            Physics - Mechanics <button
                                                class="ml-1 text-green-600 hover:text-green-800"><i
                                                    class="fas fa-times"></i></button>
                                        </span>
                                        <button class="text-xs text-primary hover:text-primary-dark flex items-center">
                                            <i class="fas fa-plus mr-1"></i> Add Bank
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Categorization</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div>
                                        <select
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                            <option>Select Subject</option>
                                            <option>Mathematics</option>
                                            <option>Physics</option>
                                            <option>Chemistry</option>
                                        </select>
                                    </div>
                                    <div>
                                        <select
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                            <option>Select Topic</option>
                                            <option>Algebra</option>
                                            <option>Calculus</option>
                                            <option>Geometry</option>
                                        </select>
                                    </div>
                                    <div>
                                        <select
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                            <option>Select Subtopic</option>
                                            <option>Linear Equations</option>
                                            <option>Quadratic Equations</option>
                                            <option>Polynomials</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="button"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2 hover:bg-gray-300">Cancel</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">Create Test
                                    Pack</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-primary text-white p-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold">Quick Actions</h2>
                        <button class="text-white hover:text-gray-200">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            <button
                                class="w-full flex items-center p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                                <i class="fas fa-plus-circle mr-3 text-lg"></i>
                                <span>Create Instant Test</span>
                            </button>
                            <button
                                class="w-full flex items-center p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition">
                                <i class="fas fa-user-shield mr-3 text-lg"></i>
                                <span>Schedule Proctored Test</span>
                            </button>
                            <button
                                class="w-full flex items-center p-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition">
                                <i class="fas fa-upload mr-3 text-lg"></i>
                                <span>Upload Question Paper</span>
                            </button>
                            <button
                                class="w-full flex items-center p-3 bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100 transition">
                                <i class="fas fa-chart-pie mr-3 text-lg"></i>
                                <span>View Analytics</span>
                            </button>
                            <button
                                class="w-full flex items-center p-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition">
                                <i class="fas fa-print mr-3 text-lg"></i>
                                <span>Print Test Paper</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Systems Tabs -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button
                            class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm border-primary text-primary">
                            Mock Test System
                        </button>
                        <button
                            class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Proctored Test System
                        </button>
                        <button
                            class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Instant Test Builder
                        </button>
                    </nav>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium">Mock Test Configuration</h3>
                        <p class="text-gray-600 text-sm">Set up timer options and access controls for mock tests</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium mb-2">Timer Options</h4>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input id="per-question" name="timer-type" type="radio"
                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300" checked>
                                    <label for="per-question" class="ml-2 block text-sm text-gray-700">
                                        Per Question Timer
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="full-test" name="timer-type" type="radio"
                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                    <label for="full-test" class="ml-2 block text-sm text-gray-700">
                                        Full Test Timer
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium mb-2">Auto Submit</h4>
                            <div class="flex items-center">
                                <input id="auto-submit" type="checkbox"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" checked>
                                <label for="auto-submit" class="ml-2 block text-sm text-gray-700">
                                    Automatically submit when time ends
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <h4 class="font-medium mb-2">Test Access</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input id="test-pack" name="access-type" type="radio"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300" checked>
                                <label for="test-pack" class="ml-2 block text-sm text-gray-700">
                                    Visible in assigned test packs
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="standalone" name="access-type" type="radio"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                <label for="standalone" class="ml-2 block text-sm text-gray-700">
                                    Standalone tests (visible to all)
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">Save
                            Configuration</button>
                    </div>
                </div>
            </div>

            <!-- Question Bank & R&D Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Question Bank -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-primary text-white p-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold">Question Bank System</h2>
                        <button class="text-white hover:text-gray-200">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="font-medium">Question Bank Structure</h3>
                                <button class="text-sm text-primary hover:text-primary-dark">View All</button>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-md">
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <span class="text-xs font-medium text-gray-500">Subject</span>
                                    <span class="text-xs font-medium text-gray-500">Topic</span>
                                    <select class="col-span-2 text-sm border border-gray-300 rounded px-2 py-1">
                                        <option>Mathematics</option>
                                        <option>Physics</option>
                                        <option>Chemistry</option>
                                    </select>
                                    <select class="col-span-2 text-sm border border-gray-300 rounded px-2 py-1">
                                        <option>Algebra</option>
                                        <option>Calculus</option>
                                        <option>Geometry</option>
                                    </select>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="font-medium text-gray-500">Total Questions: 1,245</span>
                                    <button class="text-primary hover:text-primary-dark">Advanced Filter</button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h3 class="font-medium mb-2">Question Bank Modes</h3>
                            <div class="flex space-x-2">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Public Bank
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    Private/R&D Bank
                                </span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium mb-2">Reusability</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <span class="text-xs bg-green-50 text-green-800 px-2 py-1 rounded flex items-center">
                                    <i class="fas fa-check-circle mr-1"></i> Instant Tests
                                </span>
                                <span class="text-xs bg-green-50 text-green-800 px-2 py-1 rounded flex items-center">
                                    <i class="fas fa-check-circle mr-1"></i> Mock Tests
                                </span>
                                <span class="text-xs bg-green-50 text-green-800 px-2 py-1 rounded flex items-center">
                                    <i class="fas fa-check-circle mr-1"></i> Real Tests
                                </span>
                                <span class="text-xs bg-green-50 text-green-800 px-2 py-1 rounded flex items-center">
                                    <i class="fas fa-check-circle mr-1"></i> Printed Exams
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- R&D Analytics -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-primary text-white p-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold">R&D Analytics Dashboard</h2>
                        <button class="text-white hover:text-gray-200">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="mb-4">
                            <h3 class="font-medium mb-2">Upload R&D Question Papers</h3>
                            <div
                                class="drag-area border-2 border-dashed border-gray-300 rounded-md p-4 text-center cursor-pointer hover:border-primary transition">
                                <i class="fas fa-file-upload text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500 mb-1">Upload question papers for pattern analysis</p>
                                <p class="text-xs text-gray-400">Supports PDF, DOCX, JPG formats</p>
                                <input type="file" class="hidden">
                            </div>
                        </div>
                        <div class="mb-4">
                            <h3 class="font-medium mb-2">Pattern Matching Results</h3>
                            <div class="bg-gray-50 p-3 rounded-md">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium">Algebra - Quadratic Equations</span>
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">🔥
                                        Trending</span>
                                </div>
                                <div class="text-xs text-gray-600 mb-2">Matched with previous years' papers:</div>
                                <div class="space-y-1">
                                    <div class="flex justify-between text-xs">
                                        <span>TNPSC 2023 - Q.24</span>
                                        <span class="text-green-600">92% match</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span>UPSC 2022 - Q.15</span>
                                        <span class="text-green-600">87% match</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span>SSC 2021 - Q.32</span>
                                        <span class="text-blue-600">78% match</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium mb-2">Visibility Control</h3>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input id="hidden" name="visibility" type="radio"
                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300" checked>
                                    <label for="hidden" class="ml-2 block text-sm text-gray-700">
                                        Hidden (R&D only)
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="visible" name="visibility" type="radio"
                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                    <label for="visible" class="ml-2 block text-sm text-gray-700">
                                        Visible for purchase
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vendor Module -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="bg-primary text-white p-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Vendor/Institute Module</h2>
                    <button class="text-white hover:text-gray-200">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <h3 class="font-medium mb-2">Vendor Dashboard Features</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="bg-gray-50 p-3 rounded-md">
                                <div class="flex items-center mb-1">
                                    <div
                                        class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-2">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <span class="font-medium">Test Packs</span>
                                </div>
                                <p class="text-xs text-gray-600">Create and manage test series</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-md">
                                <div class="flex items-center mb-1">
                                    <div
                                        class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-2">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <span class="font-medium">Student Management</span>
                                </div>
                                <p class="text-xs text-gray-600">Track student progress</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-md">
                                <div class="flex items-center mb-1">
                                    <div
                                        class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-2">
                                        <i class="fas fa-upload"></i>
                                    </div>
                                    <span class="font-medium">Question Banks</span>
                                </div>
                                <p class="text-xs text-gray-600">Upload and tag questions</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="font-medium mb-2">White-Labeling Options</h3>
                        <div class="bg-gray-50 p-3 rounded-md">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-sm font-medium">Institute Branding</p>
                                    <p class="text-xs text-gray-600">Custom logo, colors, and name</p>
                                </div>
                                <button class="text-sm text-primary hover:text-primary-dark">Configure</button>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium">Print Settings</p>
                                    <p class="text-xs text-gray-600">Watermark, header/footer</p>
                                </div>
                                <button class="text-sm text-primary hover:text-primary-dark">Configure</button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-medium mb-2">Use Modes</h3>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Online Platform
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Offline Printing
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                R&D Analytics
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Proctored Test Modal (Hidden by default) -->
    <div id="proctoredTestModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-screen overflow-auto">
            <div class="p-4 border-b flex justify-between items-center bg-primary text-white">
                <h3 class="text-lg font-semibold">Proctored Test in Progress</h3>
                <div class="flex items-center">
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-1"></span>
                        Recording
                    </span>
                    <span class="test-timer text-white font-mono">45:23</span>
                    <button id="closeProctoredModal" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2">
                        <div class="proctor-view rounded-lg overflow-hidden mb-4">
                            <div class="p-2 bg-dark text-white flex justify-between items-center">
                                <span>Student View</span>
                                <span class="text-xs bg-green-500 text-white px-2 py-0.5 rounded">LIVE</span>
                            </div>
                            <div class="h-64 bg-gray-800 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-user text-5xl text-gray-500 mb-2"></i>
                                    <p class="text-gray-400">Student Camera Feed</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-4">
                            <h4 class="font-medium mb-2">Test Questions</h4>
                            <div class="space-y-4">
                                <div
                                    class="question-card bg-gray-50 p-3 rounded-md border border-gray-200 transition cursor-pointer">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span
                                                class="text-xs font-medium bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Q.1</span>
                                            <p class="mt-1 text-sm">What is the value of x in the equation 2x + 5 = 15?
                                            </p>
                                        </div>
                                        <span
                                            class="text-xs font-medium bg-green-100 text-green-800 px-2 py-0.5 rounded">Answered</span>
                                    </div>
                                </div>
                                <div
                                    class="question-card bg-gray-50 p-3 rounded-md border border-gray-200 transition cursor-pointer">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span
                                                class="text-xs font-medium bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Q.2</span>
                                            <p class="mt-1 text-sm">Solve the quadratic equation x² - 5x + 6 = 0</p>
                                        </div>
                                        <span
                                            class="text-xs font-medium bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Pending</span>
                                    </div>
                                </div>
                                <div
                                    class="question-card bg-gray-50 p-3 rounded-md border border-gray-200 transition cursor-pointer">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span
                                                class="text-xs font-medium bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Q.3</span>
                                            <p class="mt-1 text-sm">Find the derivative of f(x) = 3x² + 2x - 5</p>
                                        </div>
                                        <span
                                            class="text-xs font-medium bg-gray-100 text-gray-800 px-2 py-0.5 rounded">Not
                                            Viewed</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="bg-white rounded-lg shadow p-4 mb-4">
                            <h4 class="font-medium mb-2">Test Controls</h4>
                            <div class="space-y-3">
                                <button
                                    class="w-full flex items-center justify-center p-2 bg-blue-50 text-blue-700 rounded hover:bg-blue-100">
                                    <i class="fas fa-pause mr-2"></i> Pause Test
                                </button>
                                <button
                                    class="w-full flex items-center justify-center p-2 bg-yellow-50 text-yellow-700 rounded hover:bg-yellow-100">
                                    <i class="fas fa-comment-alt mr-2"></i> Send Message
                                </button>
                                <button
                                    class="w-full flex items-center justify-center p-2 bg-red-50 text-red-700 rounded hover:bg-red-100">
                                    <i class="fas fa-flag mr-2"></i> Flag Incident
                                </button>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-4">
                            <h4 class="font-medium mb-2">Student Information</h4>
                            <div class="flex items-center mb-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold mr-3">
                                    JS</div>
                                <div>
                                    <p class="font-medium">John Smith</p>
                                    <p class="text-xs text-gray-500">ID: STU20230045</p>
                                </div>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Test:</span>
                                    <span class="font-medium">Mathematics Mock Test #3</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Started:</span>
                                    <span class="font-medium">10:15 AM</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="font-medium">60 minutes</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Questions:</span>
                                    <span class="font-medium">25/30 answered</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t flex justify-end">
                <button class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">End Test & Generate
                    Report</button>
            </div>
        </div>
    </div>

    <!-- Instant Test Builder Modal (Hidden by default) -->
    <div id="instantTestModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-screen overflow-auto">
            <div class="p-4 border-b flex justify-between items-center bg-primary text-white">
                <h3 class="text-lg font-semibold">Instant Test Builder</h3>
                <button id="closeInstantModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 p-4 rounded-lg h-full">
                            <h4 class="font-medium mb-3">Question Bank Filters</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                    <select class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                        <option>All Subjects</option>
                                        <option>Mathematics</option>
                                        <option>Physics</option>
                                        <option>Chemistry</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Topic</label>
                                    <select class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                        <option>All Topics</option>
                                        <option>Algebra</option>
                                        <option>Calculus</option>
                                        <option>Mechanics</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtopic</label>
                                    <select class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                        <option>All Subtopics</option>
                                        <option>Linear Equations</option>
                                        <option>Quadratic Equations</option>
                                        <option>Kinematics</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Difficulty</label>
                                    <select class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                        <option>Any Difficulty</option>
                                        <option>Easy</option>
                                        <option>Medium</option>
                                        <option>Hard</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Question Type</label>
                                    <select class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                        <option>Any Type</option>
                                        <option>MCQ</option>
                                        <option>Short Answer</option>
                                        <option>Long Answer</option>
                                    </select>
                                </div>
                                <button
                                    class="w-full bg-primary text-white py-2 rounded-md hover:bg-primary-dark text-sm">
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow p-4 mb-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-medium">Available Questions (142)</h4>
                                <div class="flex items-center">
                                    <input type="text" placeholder="Search questions..."
                                        class="border border-gray-300 rounded-md px-3 py-1 text-sm w-64">
                                    <button class="ml-2 p-1 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                <div
                                    class="question-card bg-gray-50 p-3 rounded-md border border-gray-200 transition cursor-pointer">
                                    <div class="flex items-start">
                                        <input type="checkbox"
                                            class="mt-1 h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                        <div class="ml-2">
                                            <p class="text-sm">What is the value of x in the equation 2x + 5 = 15?</p>
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                <span
                                                    class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Algebra</span>
                                                <span
                                                    class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded">Easy</span>
                                                <span
                                                    class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded">MCQ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="question-card bg-gray-50 p-3 rounded-md border border-gray-200 transition cursor-pointer">
                                    <div class="flex items-start">
                                        <input type="checkbox"
                                            class="mt-1 h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                                            checked>
                                        <div class="ml-2">
                                            <p class="text-sm">Solve the quadratic equation x² - 5x + 6 = 0</p>
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                <span
                                                    class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Algebra</span>
                                                <span
                                                    class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Medium</span>
                                                <span
                                                    class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded">MCQ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="question-card bg-gray-50 p-3 rounded-md border border-gray-200 transition cursor-pointer">
                                    <div class="flex items-start">
                                        <input type="checkbox"
                                            class="mt-1 h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                        <div class="ml-2">
                                            <p class="text-sm">Find the derivative of f(x) = 3x² + 2x - 5</p>
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                <span
                                                    class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Calculus</span>
                                                <span
                                                    class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Medium</span>
                                                <span
                                                    class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded">Short
                                                    Answer</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="question-card bg-gray-50 p-3 rounded-md border border-gray-200 transition cursor-pointer">
                                    <div class="flex items-start">
                                        <input type="checkbox"
                                            class="mt-1 h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                                            checked>
                                        <div class="ml-2">
                                            <p class="text-sm">A car accelerates uniformly from rest to a speed of 25
                                                m/s in 10 seconds. Calculate the acceleration.</p>
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                <span
                                                    class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Physics</span>
                                                <span
                                                    class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Medium</span>
                                                <span
                                                    class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded">Numerical</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="question-card bg-gray-50 p-3 rounded-md border border-gray-200 transition cursor-pointer">
                                    <div class="flex items-start">
                                        <input type="checkbox"
                                            class="mt-1 h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                        <div class="ml-2">
                                            <p class="text-sm">Explain the concept of conservation of momentum with
                                                examples.</p>
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                <span
                                                    class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Physics</span>
                                                <span
                                                    class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded">Hard</span>
                                                <span
                                                    class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded">Long
                                                    Answer</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-4">
                            <h4 class="font-medium mb-3">Selected Questions (3)</h4>
                            <div class="space-y-3">
                                <div class="bg-blue-50 p-3 rounded-md border border-blue-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span
                                                class="text-xs font-medium bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Q1</span>
                                            <p class="mt-1 text-sm">Solve the quadratic equation x² - 5x + 6 = 0</p>
                                        </div>
                                        <button class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="bg-blue-50 p-3 rounded-md border border-blue-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span
                                                class="text-xs font-medium bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Q2</span>
                                            <p class="mt-1 text-sm">A car accelerates uniformly from rest to a speed of
                                                25 m/s in 10 seconds. Calculate the acceleration.</p>
                                        </div>
                                        <button class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="bg-blue-50 p-3 rounded-md border border-blue-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span
                                                class="text-xs font-medium bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Q3</span>
                                            <p class="mt-1 text-sm">Find the derivative of f(x) = 3x² + 2x - 5</p>
                                        </div>
                                        <button class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow p-4 h-full">
                            <h4 class="font-medium mb-3">Test Configuration</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Test Title</label>
                                    <input type="text"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                                        placeholder="e.g. Algebra Practice Test">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Test Duration
                                        (minutes)</label>
                                    <input type="number"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" value="30">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Timer Type</label>
                                    <select class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                        <option>Full Test Timer</option>
                                        <option>Per Question Timer</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                                    <select class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                        <option>Select Student/Group</option>
                                        <option>John Smith</option>
                                        <option>Batch A</option>
                                        <option>All Students</option>
                                    </select>
                                </div>
                                <div class="pt-2">
                                    <button
                                        class="w-full bg-primary text-white py-2 rounded-md hover:bg-primary-dark text-sm">
                                        <i class="fas fa-paper-plane mr-1"></i> Create & Assign Test
                                    </button>
                                </div>
                                <div class="pt-2 border-t">
                                    <button
                                        class="w-full bg-gray-100 text-gray-700 py-2 rounded-md hover:bg-gray-200 text-sm">
                                        <i class="fas fa-print mr-1"></i> Print Test Paper
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Toggle sidebar collapse
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('collapsed');
    });

    // Mobile menu toggle
    document.getElementById('mobileMenuButton').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('open');
    });

    // Proctored test modal
    document.querySelectorAll('[data-target="proctoredTestModal"]').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('proctoredTestModal').classList.remove('hidden');
        });
    });

    document.getElementById('closeProctoredModal').addEventListener('click', function() {
        document.getElementById('proctoredTestModal').classList.add('hidden');
    });

    // Instant test modal
    document.querySelectorAll('[data-target="instantTestModal"]').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('instantTestModal').classList.remove('hidden');
        });
    });

    document.getElementById('closeInstantModal').addEventListener('click', function() {
        document.getElementById('instantTestModal').classList.add('hidden');
    });

    // Drag and drop area
    const dragAreas = document.querySelectorAll('.drag-area');
    dragAreas.forEach(area => {
        const input = area.querySelector('input[type="file"]');

        area.addEventListener('click', () => {
            input.click();
        });

        area.addEventListener('dragover', (e) => {
            e.preventDefault();
            area.classList.add('active');
        });

        area.addEventListener('dragleave', () => {
            area.classList.remove('active');
        });

        area.addEventListener('drop', (e) => {
            e.preventDefault();
            area.classList.remove('active');
            input.files = e.dataTransfer.files;
        });
    });

    // Simulate timer countdown for proctored test
    function updateTimer() {
        const timer = document.querySelector('.test-timer');
        if (timer) {
            let time = timer.textContent.split(':');
            let minutes = parseInt(time[0]);
            let seconds = parseInt(time[1]);

            if (seconds === 0) {
                if (minutes === 0) {
                    return; // Timer ends
                }
                minutes--;
                seconds = 59;
            } else {
                seconds--;
            }

            timer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
    }

    setInterval(updateTimer, 1000);

    // Question card selection
    document.querySelectorAll('.question-card').forEach(card => {
        card.addEventListener('click', function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                if (checkbox.checked) {
                    this.classList.add('border-primary', 'bg-blue-50');
                } else {
                    this.classList.remove('border-primary', 'bg-blue-50');
                }
            }
        });
    });
    </script>
</body>

</html>