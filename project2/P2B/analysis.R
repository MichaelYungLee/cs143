## R CODE FOR GRAPHICS FOR
## FINAL DELIVERABLE FOR CS143 PROJECT 2B

# If you already have R installed on your home machine, transfer the resulting files
# to your shared directory and do the visualizations in R on your home system rather than
# in the VM because R is not installed there.

# R is actually the simplest software for making plots.

##################################################
# PLOT 1: SENTIMENT OVER TIME (TIME SERIES PLOT)
###################################################

# This assumes you have a CSV file called "time_data.csv" with the columns:
# date (like 2018-08-01), Positive, Negative
# You should use the FULL PATH to the file, just in case.

time.data <- read.csv("time_data.csv", stringsAsFactors=FALSE)
time.data$date <- as.Date(time.data$date)
# Fix a small data integrity issue from the data developer.
time.data <- time.data[time.data$date != "2018-12-31", ]

# Get start and end of time series.
start <- min(time.data$date)
end <- max(time.data$date)

# Turn the data into a time series.
positive.ts <- ts(time.data$Positive, start=start, end=end)
negative.ts <- ts(time.data$Negative, start=start, end=end)

# Plot it
ts.plot(positive.ts, negative.ts, col=c("darkgreen", "red"),
        gpars=list(xlab="Day", ylab="Sentiment", main="President Trump Sentiment on /r/politics Over Time"))

################################################################
# PLOT 2: SENTIMENT BY STATE (POSITIVE AND NEGATIVE SEPARATELY)
################################################################

# May need to do 
# install.packages("ggplot2")
# install.packages("dplyr")

# This assumes you have a CSV file called "state_data.csv" with the columns:
# state, Positive, Negative
# You should use the FULL PATH to the file, just in case.

library(ggplot2)
library(dplyr)

state.data <- read.csv("state_data.csv", header=TRUE)
# rename it due to the format of the state data
state.data$region <- state.data$state
chloro <- state.data %>% mutate(region=tolower(region)) %>%
  right_join(map_data("state"))

ggplot(chloro, aes(long, lat)) +
  geom_polygon(aes(group=group, fill=Positive)) + 
  coord_quickmap() +
  scale_fill_gradient(low="#FFFFFF",high="#006400") +
  ggtitle("Positive Trump Sentiment Across the US")

ggplot(chloro, aes(long, lat)) +
  geom_polygon(aes(group=group, fill=Negative)) + 
  coord_quickmap() +
  scale_fill_gradient(low="#FFFFFF",high="#FF0000") +
  ggtitle("Negative Trump Sentiment Across the US")

################################################################
# PLOT 3: SENTIMENT DIFF BY STATE
################################################################
chloro$Difference <- chloro$Positive - chloro$Negative
ggplot(chloro, aes(long, lat)) +
  geom_polygon(aes(group=group, fill=Difference)) + 
  coord_quickmap() +
  scale_fill_gradient(low="#FFFFFF",high="#000000") +
  ggtitle("Difference in Sentiment Across the US")

##################################
# PART 4 SHOULD BE DONE IN SPARK
##################################

########################################
# PLOT 5A: SENTIMENT BY STORY SCORE
########################################
# What is the purpose of this? It helps us determine if the story score
# should be a feature in the model. Remember that /r/politics is pretty
# biased.

# Assumes a CSV file called submission_score.csv with the following coluns
# submission_score, Positive, Negative

submission.data <- read.csv("submission_score.csv", quote="", header=TRUE)
plot(Positive~story_score, data=story.data, col='darkgreen', pch='.',
     main="Sentiment By Score on Submission")
points(Negative~story_score, data=story.data, col='red', pch='.')


########################################
# PLOT 5B: SENTIMENT BY COMMENT SCORE
########################################
# What is the purpose of this? It helps us determine if the story score
# should be a feature in the model. Remember that /r/politics is pretty
# biased.

# Assumes a CSV file called comment_score.csv with the following columns
# comment_score, Positive, Negative

comment.data <- read.csv("comment_score.csv", quote="", header=TRUE)
plot(Positive~comment_score, data=comment.data, col='darkgreen', pch='.',
     main="Sentiment By Score on Comments")
points(Negative~comment_score, data=comment.data, col='red', pch='.')