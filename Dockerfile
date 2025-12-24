FROM php:8.2-cli

# Set working directory
WORKDIR /app

# Copy application files
COPY . /app

# Create data directory with proper permissions
RUN mkdir -p /app/data && chmod -R 777 /app/data

# Expose port
EXPOSE 10000

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:10000"]
