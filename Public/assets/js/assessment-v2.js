function assessmentApp() {
    return {
        // State
        view: 'dashboard',
        loading: false,
        assessmentId: 0,
        resultId: 0,
        currentIndex: 0,
        totalQuestions: 0,
        currentQuestion: { text: '', options: [] },
        selectedAnswer: null,
        isLast: false,
        startTime: null,
        elapsedTime: '00:00',
        timerInterval: null,
        saving: false,

        // Completion state
        finalPercentage: 0,
        finalAnswered: 0,
        assessmentName: '',
        allCompleted: false,

        // Question order
        questionIds: [],
        answeredCount: 0,

        // Assessment config (from PHP)
        assessmentNames: { 1: 'Personality', 2: 'Interest', 3: 'Aptitude', 4: 'Career Values' },
        assessmentLimits: { 1: 8, 2: 8, 3: 10, 4: 6 },

        // Recommendation data
        hasRecommendation: false,
        recommendation: null,
        recommendationUrl: '',

        init() {
            this.loadDashboard();
        },

        getBaseUrl() {
            var scripts = document.getElementsByTagName('script');
            var src = scripts[scripts.length - 1].src;
            var parts = src.split('/');
            parts.pop();
            parts.pop();
            parts.pop();
            return parts.join('/');
        },

        apiUrl(page) {
            return this.getBaseUrl() + '/index.php?page=' + page;
        },

        loadDashboard() {
            this.view = 'dashboard';
            if (this.timerInterval) { clearInterval(this.timerInterval); this.timerInterval = null; }
        },

        startAssessment(id) {
            this.assessmentId = id;
            this.loading = true;
            this.view = 'loading';
            this.currentIndex = 0;
            this.selectedAnswer = null;
            this.startTime = new Date();

            var self = this;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', this.apiUrl('v2-assessment-api-start'), true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            var data = JSON.parse(xhr.responseText);
                            if (data.success) {
                                self.resultId = data.result_id;
                                self.totalQuestions = data.total_questions;
                                self.answeredCount = data.answered;
                                self.loadQuestion(0);
                                self.startTimer();
                            } else {
                                self.view = 'dashboard';
                                if (data.redirect) {
                                    window.location.href = self.getBaseUrl() + '/index.php?page=student-assessments-v2';
                                }
                            }
                        } catch(e) { self.view = 'dashboard'; }
                    } else { self.view = 'dashboard'; }
                }
            };
            xhr.send(JSON.stringify({ assessment_id: id }));
        },

        loadQuestion(index) {
            this.loading = true;
            this.view = 'loading';
            this.selectedAnswer = null;
            this.isLast = false;
            var self = this;

            var xhr = new XMLHttpRequest();
            xhr.open('GET', this.apiUrl('v2-assessment-api-question') + '&result_id=' + this.resultId + '&index=' + index, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            var data = JSON.parse(xhr.responseText);
                            if (data.success) {
                                self.currentIndex = index;
                                self.currentQuestion = data.question;
                                self.selectedAnswer = data.selected || null;
                                self.isLast = data.navigation.is_last;
                                self.totalQuestions = data.progress.total;
                                self.answeredCount = data.progress.answered;
                                self.loading = false;
                                self.view = 'taking';
                                self.$nextTick(function() {
                                    // Alpine re-renders automatically with x-for
                                });
                            } else if (data.done) {
                                self.finishAssessment();
                            } else {
                                self.view = 'dashboard';
                            }
                        } catch(e) { self.view = 'dashboard'; }
                    } else { self.view = 'dashboard'; }
                }
            };
            xhr.send();
        },

        selectAnswer(key) {
            this.selectedAnswer = key;
        },

        prevQuestion() {
            if (this.currentIndex > 0) {
                this.loadQuestion(this.currentIndex - 1);
            }
        },

        nextQuestion() {
            if (!this.selectedAnswer) return;
            if (this.saving) return;
            this.saving = true;

            var self = this;
            var questionId = this.currentQuestion.id;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', this.apiUrl('v2-assessment-api-save'), true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    self.saving = false;
                    if (xhr.status >= 200 && xhr.status < 300) {
                        if (self.isLast) {
                            self.finishAssessment();
                        } else {
                            self.loadQuestion(self.currentIndex + 1);
                        }
                    }
                }
            };
            xhr.send(JSON.stringify({ question_id: questionId, answer: this.selectedAnswer }));
        },

        finishAssessment() {
            if (this.saving) return;
            this.saving = true;
            var self = this;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', this.apiUrl('v2-assessment-api-finish'), true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    self.saving = false;
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            var data = JSON.parse(xhr.responseText);
                            if (data.success) {
                                self.finalPercentage = data.percentage;
                                self.finalAnswered = data.answered;
                                self.assessmentName = data.assessment_name;
                                self.allCompleted = data.all_completed;
                                self.hasRecommendation = data.all_completed;
                                self.recommendationUrl = self.getBaseUrl() + '/index.php?page=career-recommendation';
                                self.view = 'completed';
                                if (self.timerInterval) { clearInterval(self.timerInterval); }
                                // Trigger confetti celebration
                                self.$nextTick(function() {
                                    self.triggerConfetti();
                                });
                            }
                        } catch(e) {}
                    }
                }
            };
            xhr.send(JSON.stringify({ assessment_id: this.assessmentId }));
        },

        exitAssessment() {
            if (confirm('Exit assessment? Your progress is saved.')) {
                if (this.timerInterval) { clearInterval(this.timerInterval); }
                this.view = 'dashboard';
            }
        },

        startTimer() {
            if (this.timerInterval) clearInterval(this.timerInterval);
            var self = this;
            var start = this.startTime || new Date();

            function update() {
                var now = new Date();
                var diff = Math.floor((now - start) / 1000);
                var mins = Math.floor(diff / 60);
                var secs = diff % 60;
                self.elapsedTime = (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
            }
            update();
            this.timerInterval = setInterval(update, 1000);
        },

        // Completion view computed properties
        get circumference() {
            return 2 * Math.PI * 80; // radius = 80
        },

        get scoreColor() {
            var score = this.finalPercentage;
            if (score >= 71) return '#059669'; // Green
            if (score >= 41) return '#D97706'; // Orange
            return '#EF4444'; // Red
        },

        get scoreLabel() {
            var score = this.finalPercentage;
            if (score >= 85) return 'Excellent';
            if (score >= 71) return 'Great Job';
            if (score >= 55) return 'Good';
            if (score >= 41) return 'Fair';
            return 'Keep Practicing';
        },

        get performanceStars() {
            var score = this.finalPercentage;
            if (score >= 90) return 5;
            if (score >= 75) return 4;
            if (score >= 60) return 3;
            if (score >= 40) return 2;
            return 1;
        },

        get performanceTitle() {
            var score = this.finalPercentage;
            if (score >= 85) return 'Outstanding Performance';
            if (score >= 71) return 'Strong Match';
            if (score >= 55) return 'Good Understanding';
            if (score >= 41) return 'Developing';
            return 'Room for Growth';
        },

        get performanceDescription() {
            var score = this.finalPercentage;
            if (score >= 85) return 'You have demonstrated exceptional understanding in this assessment area.';
            if (score >= 71) return 'You have demonstrated a clear understanding of your work preferences and values.';
            if (score >= 55) return 'You have a solid foundation with room to explore further.';
            if (score >= 41) return 'You are developing your understanding in this area. Keep exploring!';
            return 'This area offers opportunities for growth. Consider retaking after more exploration.';
        },

        get performanceIconClass() {
            var score = this.finalPercentage;
            if (score >= 71) return 'bg-emerald-100 text-emerald-600';
            if (score >= 41) return 'bg-amber-100 text-amber-600';
            return 'bg-red-100 text-red-600';
        },

        get matchColorClass() {
            var match = this.recommendation ? this.recommendation.match : 0;
            if (match >= 80) return 'bg-emerald-100 text-emerald-700';
            if (match >= 60) return 'bg-blue-100 text-blue-700';
            if (match >= 40) return 'bg-amber-100 text-amber-700';
            return 'bg-slate-100 text-slate-700';
        },

        // Confetti celebration
        triggerConfetti() {
            var container = document.getElementById('confettiContainer');
            if (!container) return;

            var colors = ['#5B5FEF', '#7C3AED', '#06B6D4', '#10B981', '#F59E0B', '#EF4444'];
            var confettiCount = 50;

            for (var i = 0; i < confettiCount; i++) {
                var confetti = document.createElement('div');
                confetti.className = 'confetti-piece';
                confetti.style.cssText = 
                    'position: absolute;' +
                    'width: ' + (Math.random() * 8 + 6) + 'px;' +
                    'height: ' + (Math.random() * 8 + 6) + 'px;' +
                    'background: ' + colors[Math.floor(Math.random() * colors.length)] + ';' +
                    'left: ' + (Math.random() * 100) + '%;' +
                    'top: -20px;' +
                    'opacity: 0;' +
                    'transform: rotate(' + (Math.random() * 360) + 'deg);' +
                    'border-radius: ' + (Math.random() > 0.5 ? '50%' : '4px') + ';' +
                    'animation: confettiFall ' + (Math.random() * 1.5 + 2) + 's cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;' +
                    'animation-delay: ' + (Math.random() * 0.5) + 's;' +
                    'pointer-events: none;';
                container.appendChild(confetti);
            }

            // Clean up after animation
            setTimeout(function() {
                while (container.firstChild) {
                    container.removeChild(container.firstChild);
                }
            }, 4000);
        }
    };
}
