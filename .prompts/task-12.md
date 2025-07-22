Prompt:
I’m getting this error in production:

PHP Fatal error: Uncaught Error: Class "PBCRAgentMode\Blocks\AgentCopyButton" not found in /wp-content/plugins/pbcr-agent-mode/includes/Loader.php:50

Please investigate in the codebase and answer:
	1.	Where is AgentCopyButton defined?
	2.	Is the namespace correct and matches the usage in Loader.php?
	3.	Does the file structure follow PSR-4 autoloading rules (check composer.json)?
	4.	Does Loader.php properly include or autoload this class?
	5.	Suggest the best fix (autoload config or manual require).

Return:
	•	The file path where the class should be.
	•	The expected namespace.
	•	If PSR-4 mapping is correct.
	•	Any inconsistencies (case sensitivity, missing file, wrong path).
	•	A code snippet with the fix.
