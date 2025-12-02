</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- MathJax: configure before loading the library -->
<script>
window.MathJax = {
	tex: { inlineMath: [['$','$'], ['\\(', '\\)']] },
	options: { skipHtmlTags: ['script','noscript','style','textarea','pre','code'] },
	startup: { typeset: false }
};
</script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<script src="assets/js/main.js"></script>

<!-- Trigger typeset after all page scripts have run (safe & idempotent) -->
<script>
(function(){
	function typesetNow(){
		if(window.MathJax && MathJax.typesetPromise){
			MathJax.typesetPromise().catch(function(err){
				console.error('MathJax typeset error:', err);
			});
		}
	}

	// Run after full load so other scripts (that may inject math) have executed
	if(document.readyState === 'complete'){
		typesetNow();
	} else {
		window.addEventListener('load', typesetNow);
	}
})();
</script>
</body>

</html>