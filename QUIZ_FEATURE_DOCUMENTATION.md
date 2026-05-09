# Quiz Feature Documentation

## ✅ Setup Complete

All quiz features are now fully configured and ready to use!

---

## 📊 Database Status

- **Migrations**: ✅ All run successfully
- **Categories**: 4 (VE, VI, QSER, PS)
- **Questions**: 55 total
  - VE (Vérifications Extérieures): 19 questions
  - VI (Vérifications Intérieures): 16 questions
  - QSER (Questions de Sécurité Routière): 10 questions
  - PS (Premiers Secours): 10 questions

---

## 🔌 API Endpoints

All endpoints require authentication (Bearer token via Sanctum).

### 1. Get Quiz Categories
```
GET /api/quiz/categories
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "code": "VE",
      "name": "Vérifications Extérieures",
      "description": "...",
      "pass_score": 15,
      "total_questions": 19,
      "last_score": null,
      "last_passed": false,
      "total_attempts": 0,
      "passed_attempts": 0
    }
  ]
}
```

### 2. Start a Quiz
```
POST /api/quiz/categories/{categoryId}/start
```

**Request Body:**
```json
{
  "question_count": 20  // Optional, default: 20, min: 5, max: 20
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "attempt_id": 1,
    "category": {
      "id": 1,
      "code": "VE",
      "name": "Vérifications Extérieures"
    },
    "questions": [
      {
        "id": 1,
        "question_number": 1,
        "question_text": "Question text here?",
        "options": [
          "Option A",
          "Option B",
          "Option C",
          "Option D"
        ]
      }
    ],
    "total_questions": 20,
    "pass_score": 15
  }
}
```

### 3. Submit Quiz Answers
```
POST /api/quiz/attempts/{attemptId}/submit
```

**Request Body:**
```json
{
  "answers": [
    {
      "question_id": 1,
      "answer": 0  // 0=A, 1=B, 2=C, 3=D
    },
    {
      "question_id": 2,
      "answer": 2
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "score": 18,
    "total_questions": 20,
    "passed": true,
    "pass_score": 15,
    "results": [
      {
        "question_id": 1,
        "question_number": 1,
        "question_text": "...",
        "options": ["A", "B", "C", "D"],
        "user_answer_index": 0,
        "correct_answer_index": 0,
        "user_answer": "Option A",
        "correct_answer": "Correct answer text",
        "is_correct": true
      }
    ]
  }
}
```

### 4. Get Quiz History
```
GET /api/quiz/history
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "category": {
        "code": "VE",
        "name": "Vérifications Extérieures"
      },
      "score": 18,
      "total_questions": 20,
      "passed": true,
      "completed_at": "2026-03-31 15:30:00"
    }
  ]
}
```

### 5. Get Attempt Details
```
GET /api/quiz/attempts/{attemptId}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "category": {
      "code": "VE",
      "name": "Vérifications Extérieures"
    },
    "score": 18,
    "total_questions": 20,
    "passed": true,
    "completed_at": "2026-03-31 15:30:00",
    "answers": [
      {
        "question_number": 1,
        "question_text": "...",
        "options": ["A", "B", "C", "D"],
        "user_answer_index": 0,
        "correct_answer_index": 0,
        "user_answer": "Option A",
        "correct_answer": "Correct answer",
        "is_correct": true
      }
    ]
  }
}
```

---

## 🗂️ Database Structure

### Tables

#### quiz_categories
- `id` - Primary key
- `code` - Unique code (VE, VI, QSER, PS)
- `name` - Category name
- `description` - Category description
- `pass_score` - Minimum score to pass (default: 15/20)

#### quiz_questions
- `id` - Primary key
- `category_id` - Foreign key to quiz_categories
- `question_number` - Original question number
- `question_text` - The question
- `correct_answer` - Text of correct answer
- `correct_option_index` - Index of correct option (0-3)
- `options` - JSON array of 4 options

#### quiz_attempts
- `id` - Primary key
- `user_id` - Foreign key to users
- `category_id` - Foreign key to quiz_categories
- `score` - Score achieved
- `total_questions` - Number of questions in attempt
- `passed` - Boolean if passed
- `started_at` - When quiz started
- `completed_at` - When quiz completed

#### quiz_answers
- `id` - Primary key
- `attempt_id` - Foreign key to quiz_attempts
- `question_id` - Foreign key to quiz_questions
- `user_answer` - User's answer (stored as index 0-3)
- `is_correct` - Boolean if correct

---

## 📝 Models

All models are located in `app/Models/`:
- `QuizCategory.php`
- `QuizQuestion.php`
- `QuizAttempt.php`
- `QuizAnswer.php`

All relationships are properly configured.

---

## 🧪 Testing

### Test User Created
- **Email**: test@example.com
- **Password**: password
- **Role**: learner

### Manual Testing Steps

1. **Login to get token:**
```bash
POST /api/login
{
  "email": "test@example.com",
  "password": "password"
}
```

2. **Get categories:**
```bash
GET /api/quiz/categories
Authorization: Bearer {token}
```

3. **Start a quiz:**
```bash
POST /api/quiz/categories/1/start
Authorization: Bearer {token}
{
  "question_count": 10
}
```

4. **Submit answers:**
```bash
POST /api/quiz/attempts/{attempt_id}/submit
Authorization: Bearer {token}
{
  "answers": [
    {"question_id": 1, "answer": 0},
    {"question_id": 2, "answer": 1}
  ]
}
```

---

## 🎯 Features

✅ Multiple quiz categories (VE, VI, QSER, PS)
✅ Random question selection
✅ Configurable question count (5-20)
✅ Multiple choice questions (4 options)
✅ Automatic scoring
✅ Pass/fail determination
✅ Quiz history tracking
✅ Detailed attempt results
✅ User progress tracking per category
✅ Authentication required
✅ Full API documentation

---

## 🔄 Adding More Questions

To add more questions, edit the seeder file:
```
database/seeders/QuizSeederMultipleChoice.php
```

Then run:
```bash
php artisan db:seed --class=QuizSeederMultipleChoice
```

---

## 🚀 Next Steps

Your quiz feature is fully functional! You can now:

1. **Integrate with frontend** - Use the API endpoints above
2. **Add more questions** - Edit the seeder and re-run
3. **Customize pass scores** - Update in quiz_categories table
4. **Add analytics** - Track user performance over time
5. **Add time limits** - Extend the QuizAttempt model
6. **Add question explanations** - Add field to quiz_questions table

---

## 📞 Support

If you need to:
- Add new categories
- Modify pass scores
- Add more questions
- Change quiz logic

Just let me know!

---

**Status**: ✅ READY FOR PRODUCTION
**Last Updated**: March 31, 2026
