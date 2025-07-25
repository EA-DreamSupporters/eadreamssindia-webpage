# Test Management System (TMS) + Instant Test Builder + R&D Analytics + Vendor Expansion

---

## 1. Core Test Management System (TMS)

### A. Test Pack Creation (Admin Panel)

- **Fields:**
  - Title
  - Cover Image
  - Selling Price & MRP
  - Attach Linked Question Bank(s)
- **Categorization:**
  - Subject → Topic → Subtopic tagging
  - Required for easy retrieval & filtering

### B. Mock Test System

- **Timer Options:**
  - Per Question Timer
  - Full Test Timer
- **Auto Submit:**
  - When time ends (per question or full test)
- **Access:**
  - Via internal student dashboard
  - Visible in assigned test packs or as standalone tests

### C. Real Test System (Proctored Tests)

- **Slot Booking:**
  - Student chooses preferred slot
- **Proctoring Features:**
  - Webcam monitoring (student visible to proctor, not vice versa)
  - Indicator: "Green Light - Proctored Test in Progress"
  - Optionally record test session
- **Timer & Auto Submit:**
  - Supports both per-question and full-test modes
- **Post-Test Reports:**
  - Analytics for each test
  - Personalized mentoring session booking
  - Reports available via meeting links or download

---

## 2. Instant Test Builder System

### A. Dynamic Test Creation

- **Request Type:**
  - Student requests instant test via dashboard/chat
- **Test Creator Workflow:**
  - Search questions by topic, subtopic, subject
  - Tick/select from different banks across topics
  - Assemble and "Create as Paper"
- **Output:**
  - Instant Test Link generated
  - Record linked to student’s dashboard

### B. Printing Mode Support

- **Print Config Options:**
  - Branding: Institute logo, watermark
  - Exam layout templates (MCQ grid, paragraph type)
  - Downloadable as PDF/Print-ready format

---

## 3. Question Bank System

### A. Structure

- **Centralized DB with tagging:**
  - Exam Year, Source
  - Subject, Topic, Subtopic
- **Modes:**
  - Public Bank (for sale or mock tests)
  - Private/R&D Bank (for pattern analysis)

### B. Reusability

- Questions can be pulled into:
  - Instant Tests
  - Mock Tests
  - Real Tests
  - Printed Exams

---

## 4. R&D + Prediction Engine

### A. Upload R&D Question Papers

- Upload full question paper (e.g. TNPSC 2024)
- AI or tagging system segments them into questions
- Each question is linked to subject, topic, subtopic

### B. Smart Pattern Matching

- System matches questions with:
  - Previous years’ papers
  - Existing question bank
- Outcome:
  - Identify repeated topics
  - List which year had similar Qs

### C. AI Analytics Dashboard

- **Visuals:**
  - Repeated Questions Map
  - Heatmap of topic repetition
  - Subject dominance over years
- **Purpose:**
  - Identify high-value questions
  - Predict likely repeat areas

### D. Visibility Toggle

- Questions/Papers can be:
  - Hidden (only usable for building tests)
  - Visible with pricing (listed for purchase)

### E. Integration

- R&D questions usable in:
  - Instant Test Builder
  - Printable papers
  - Analytics and reports

---

## 5. Vendor/Institute Module (Multi-Tenant System)

### A. New Dashboard for Vendors

- Role-based Admin Panel
- Can create:
  - Test Packs
  - Instant/Mock/Real Tests
  - Manage Students (optional)
  - Upload their own question banks (auto-tagged)

### B. White-Labeling Options

- Their branding, logos, institute name
- Their own test pricing, watermarking, print settings

### C. Use Modes

- Online test platform
- Offline exam printing system
- R&D/Analytics module (optional add-on)

---

## 6. Internal Infrastructure & Engagement Strategy

### A. What You Need to Build

- Super Admin Dashboard (You)
- Institute/Vendor Dashboard
- Question Bank Manager with tags and import/export
- Pattern Recognition AI Engine
- Instant Test Builder Interface (Drag, Filter, Search)
- Proctored Test Scheduler + Observer Mode
- Mentoring & Report Generation Flow
- UI for printing and watermark settings

### B. What Other Institutes Need

- Custom Dashboard (branded)
- Ability to manage their test series
- Upload & tag their own questions
- Access to print/download questions
- Optionally: access to AI predictions

---

## 7. User Experience Design Principle

### A. Keep it Modular

- Each system (Mock, Real, Instant, R&D) works independently

### B. Minimize Complexity

- Smart filters, simple toggles, drag-and-drop UX

### C. Fun to Build & Use

- Dynamic dashboards with cards, colors, and charts
- Gamified analytics (e.g., "🔥 Trending Topics")
- Personalization: AI suggests best time to schedule mock tests

---

This structure ensures your platform becomes:
✅ Test Prep Suite
✅ Instant Test Factory
✅ Predictive Intelligence Engine
✅ Exam Print Press
✅ Coaching Brand Booster

This is a full description of how the system should work. All development and feature implementation should follow these instructions until the system is complete. Refine as needed, but always align with this prompt.
