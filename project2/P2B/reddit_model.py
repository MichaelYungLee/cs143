from __future__ import print_function
from pyspark import SparkConf, SparkContext
from pyspark.sql import SQLContext

# IMPORT OTHER MODULES HERE
from cleantext import sanitize
from pyspark.sql.functions import *
from pyspark.sql.types import StringType, ArrayType
from pyspark.ml.feature import CountVectorizer

# Bunch of imports (may need more)
from pyspark.ml.classification import LogisticRegression
from pyspark.ml.tuning import CrossValidator, ParamGridBuilder
from pyspark.ml.evaluation import BinaryClassificationEvaluator

states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'District of Columbia', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming']

def main(context):
    """Main function takes a Spark SQL context."""
    # YOUR CODE HERE
    # YOU MAY ADD OTHER FUNCTIONS AS NEEDED

    # TASK 1

    comments = context.read.json("comments-minimal.json.bz2")
    submissions = context.read.json("submissions.json.bz2")
    labeled = context.read.format("csv").option("header", "true").load("labeled_data.csv")
    # comments.write.parquet("comments.parquet")
    # submissions.write.parquet("submissions.parquet")
    # labeled.write.parquet("labeled.parquet")
    #comments = context.read.parquet("comments.parquet")
    #submissions = context.read.parquet("submissions.parquet")
    #labeled = context.read.parquet("labeled.parquet")

    # TASK 2
    joinedDF = labeled.join(comments, labeled.Input_id == comments.id, "inner").drop('id')

    # TASK 4
    sanitize_udf = udf(sanitize, ArrayType(StringType()))
    sanitizedDF = joinedDF.select('Input_id', 
    				'labeldjt',
    				sanitize_udf('body').alias('body_sanitized'))

    # TASK 5
    combine_ngrams_udf = udf(combine_ngrams, ArrayType(StringType()))
    combinedDF = sanitizedDF.select('Input_id',
    								'labeldjt',
    								combine_ngrams_udf('body_sanitized').alias('body_combined'))

    # TASK 6A
    cv = CountVectorizer(inputCol="body_combined", outputCol="features", minDF = 5.0, binary=True)
    model = cv.fit(combinedDF)
    result = model.transform(combinedDF)


    # TASK 6B
    pos = result.select('Input_id',
    	'labeldjt',
    	'features',
    	when(result.labeldjt == 1, 1).otherwise(0).alias('label'))

    neg = result.select('Input_id',
    	'labeldjt',
    	'features',
    	when(result.labeldjt == -1, 1).otherwise(0).alias('label'))

    # TASK 7
    # Initialize two logistic regression models.
    # Replace labelCol with the column containing the label, and featuresCol with the column containing the features.
    poslr = LogisticRegression(labelCol="label", featuresCol="features", maxIter=10).setThreshold(0.2)
    neglr = LogisticRegression(labelCol="label", featuresCol="features", maxIter=10).setThreshold(0.25)

    # This is a binary classifier so we need an evaluator that knows how to deal with binary classifiers.
    posEvaluator = BinaryClassificationEvaluator()
    negEvaluator = BinaryClassificationEvaluator()
    # There are a few parameters associated with logistic regression. We do not know what they are apriori.
    # We do a grid search to find the best parameters. We can replace [1.0] with a list of values to try.
    # We will assume the parameter is 1.0. Grid search takes forever.
    posParamGrid = ParamGridBuilder().addGrid(poslr.regParam, [1.0]).build()
    negParamGrid = ParamGridBuilder().addGrid(neglr.regParam, [1.0]).build()

    # We initialize a 5 fold cross-validation pipeline.
    posCrossval = CrossValidator(
    	estimator=poslr,
    	evaluator=posEvaluator,
    	estimatorParamMaps=posParamGrid,
    	numFolds=5)
    negCrossval = CrossValidator(
    	estimator=neglr,
    	evaluator=negEvaluator,
    	estimatorParamMaps=negParamGrid,
    	numFolds=5)

    # Although crossvalidation creates its own train/test sets for
    # tuning, we still need a labeled test set, because it is not
    # accessible from the crossvalidator (argh!)
    # Split the data 50/50
    posTrain, posTest = pos.randomSplit([0.5, 0.5])
    negTrain, negTest = neg.randomSplit([0.5, 0.5])

    # Train the models
    print("Training positive classifier...")
    posModel = posCrossval.fit(posTrain)
    print("Training negative classifier...")
    negModel = negCrossval.fit(negTrain)

    # TASK 8 (and a bit of TASK 9)
    comments = context.read.json("comments-minimal.json.bz2")
    submissions = context.read.json("submissions.json.bz2")
    labeled = context.read.format("csv").option("header", "true").load("labeled_data.csv")

    remove_t3_udf = udf(remove_t3_, StringType())
    commentsFiltered = comments.select(col('id').alias('comment_id'),
    	'author_flair_text',
    	'created_utc',
    	'body',
    	col('score').alias('comment_score'),
    	remove_t3_udf('link_id').alias('link_id')).filter(~comments.body.contains('/s')).filter(~comments.body.startswith('&gt;'))
    finalDF = commentsFiltered.join(submissions.select('id','title',col('score').alias('submission_score')), commentsFiltered.link_id == submissions.id, "inner").drop('id')

    # TASK 9
    sanitizedFinalDF = finalDF.select('comment_id', 
    	'author_flair_text',
    	'created_utc',
    	'title',
    	'comment_score',
    	'submission_score',
    	sanitize_udf('body').alias('body_sanitized'))

    combinedFinalDF = sanitizedFinalDF.select('comment_id',
    	'author_flair_text',
    	'created_utc',
    	'title',
    	'comment_score',
    	'submission_score',
    	combine_ngrams_udf('body_sanitized').alias('body_combined'))

    resultFinalDF = model.transform(combinedFinalDF)
    posResult = posModel.transform(resultFinalDF)
    finalResult = posResult.select('comment_id',
    	'author_flair_text',
    	'created_utc',
    	'title',
    	'comment_score',
    	'submission_score',
    	'body_combined',
    	'features',
    	col('prediction').alias('positive'))
    negResult = negModel.transform(resultFinalDF)
    finalResult=negResult.select('comment_id',
    	'author_flair_text',
    	'created_utc',
    	'title',
    	'comment_score',
    	'submission_score',
    	'body_combined',
    	'features',
    	'positive',
    	col('prediction').alias('negative'))
    finalResult.write.parquet("finalResult.parquet")
    frParquet = context.read.parquet("finalResult.parquet")

    # TASK 10
    # 1 (across submissions/posts)
    frParquet.groupBy('title').agg((sum('positive')/count('positive')).alias('Positive'),count('positive').alias('Count')).orderBy(desc('Positive'), desc('Count')).show(10, truncate=False)
    frParquet.groupBy('title').agg((sum('negative')/count('negative')).alias('Negative'),count('negative').alias('Count')).orderBy(desc('Negative'), desc('Count')).show(10, truncate=False)
    # 2 (across dates)
    time_data = frParquet.groupBy(from_unixtime('created_utc').cast('date').alias('date')).agg((sum('positive')/count('positive')).alias('Positive'),(sum('negative')/count('negative')).alias('Negative')).orderBy(asc('date'))
    # 3 (across states)
    state_data = frParquet[frParquet.author_flair_text.isin(states)].groupBy(col('author_flair_text').alias('state')).agg((sum('positive')/count('positive')).alias('Positive'),(sum('negative')/count('negative')).alias('Negative')).orderBy(asc('state'))
    # 4 (across submission_scores/comment_scores)
    # submission_score
    submission_score = frParquet.groupBy('submission_score').agg((sum('positive')/count('positive')).alias('Positive'),(sum('negative')/count('negative')).alias('Negative'))
    # comment_score
    comment_score = frParquet.groupBy('comment_score').agg((sum('positive')/count('positive')).alias('Positive'),(sum('negative')/count('negative')).alias('Negative'))
    time_data.repartition(1).write.format("com.databricks.spark.csv").option("header","true").save("time_data.csv")
    state_data.repartition(1).write.format("com.databricks.spark.csv").option("header","true").save("time_data.csv")
    submission_score.repartition(1).write.format("com.databricks.spark.csv").option("header","true").save("time_data.csv")
    comment_score.repartition(1).write.format("com.databricks.spark.csv").option("header","true").save("time_data.csv")

def combine_ngrams(text):
	combined_ngrams_array = []
	for ngram in text:
		ngram = ngram.split()
		for gram in ngram:
			combined_ngrams_array.append(gram)
	return combined_ngrams_array

def remove_t3_(text):
	return text.replace("t3_", "")

if __name__ == "__main__":
    conf = SparkConf().setAppName("CS143 Project 2B")
    conf = conf.setMaster("local[*]")
    sc   = SparkContext(conf=conf)
    sqlContext = SQLContext(sc)
    sc.addPyFile("cleantext.py")
    main(sqlContext)
