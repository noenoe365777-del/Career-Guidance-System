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
            this.view = 'question';
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
                                // Reattach click handlers for the newly rendered options
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
                                self.view = 'complete';
                                if (self.timerInterval) { clearInterval(self.timerInterval); }
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
    };
}
